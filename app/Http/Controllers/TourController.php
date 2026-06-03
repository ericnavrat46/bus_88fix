<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use App\Models\TourBooking;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function index()
    {
        $packages = TourPackage::where('status', 'active')->latest()->get();
        return view('tour.index', [
            'packages' => $packages
        ]);
    }

    public function show(TourPackage $package)
    {
        if ($package->status !== 'active') {
            abort(404);
        }

        $package->load(['reviews.user']);
        $reviews = $package->reviews()->where('is_visible', true)->latest()->get();

        return view('tour.show', compact('package', 'reviews'));
    }

    public function bookingForm(TourPackage $package)
    {
        if ($package->status !== 'active') {
            abort(404);
        }
        $buses = \App\Models\Bus::where('status', 'active')->get();
        return view('tour.booking', compact('package', 'buses'));
    }

    public function storeBooking(Request $request, TourPackage $package)
    {
        $validated = $request->validate([
            'travel_date' => 'required|date|after:today',
            'passenger_count' => 'required|integer|min:1',
            'bus_id' => 'required|exists:buses,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'applied_promo_id' => 'nullable|exists:promo_banners,id',
        ]);

        // Validasi Ketersediaan Bus
        $startDate = \Carbon\Carbon::parse($validated['travel_date']);
        $endDate = $startDate->copy()->addDays($package->duration_days - 1);

        // Cek bentrok dengan Jadwal Reguler (Schedule)
        $conflictSchedule = \App\Models\Schedule::where('bus_id', $validated['bus_id'])
            ->where('status', '!=', 'cancelled')
            ->whereDate('departure_date', '>=', $startDate)
            ->whereDate('departure_date', '<=', $endDate)
            ->exists();

        if ($conflictSchedule) {
            return back()->withInput()->with('error', 'Bus ini sudah memiliki jadwal reguler pada rentang tanggal tersebut.');
        }

        // Cek bentrok dengan Rental lain
        $conflictRental = \App\Models\Rental::where('bus_id', $validated['bus_id'])
            ->where('approval_status', '!=', 'rejected')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        if ($conflictRental) {
            return back()->withInput()->with('error', 'Bus ini sedang disewa (Rental) pada rentang tanggal tersebut.');
        }

        // Cek bentrok dengan Tour Booking lain
        $conflictTour = \App\Models\TourBooking::where('bus_id', $validated['bus_id'])
            ->whereIn('payment_status', ['paid', 'pending'])
            ->join('tour_packages', 'tour_bookings.tour_package_id', '=', 'tour_packages.id')
            ->whereRaw("? <= DATE_ADD(travel_date, INTERVAL tour_packages.duration_days - 1 DAY) AND ? >= travel_date", [$endDate->toDateString(), $startDate->toDateString()])
            ->exists();

        if ($conflictTour) {
            return back()->withInput()->with('error', 'Bus ini sudah dipesan untuk paket wisata lain pada rentang tanggal tersebut.');
        }

        $subtotal = $package->final_price; // Flat package price (Private Tour)
        $user = auth()->user();

        $booking = DB::transaction(function () use ($package, $validated, $subtotal, $user) {
            $discountAmount = 0;
            $promo = null;

            if (!empty($validated['applied_promo_id'])) {
                $promo = \App\Models\PromoBanner::find($validated['applied_promo_id']);
                if ($promo && $promo->isValidFor('tour')) {
                    $discountAmount = $promo->calculateDiscount($subtotal);
                    $promo->increment('used_quota');
                }
            }

            $totalPrice = $subtotal - $discountAmount;

            $booking = TourBooking::create([
                'booking_code' => TourBooking::generateBookingCode(),
                'user_id' => $user->id,
                'tour_package_id' => $package->id,
                'promo_banner_id' => $promo ? $promo->id : null,
                'travel_date' => $validated['travel_date'],
                'passenger_count' => $validated['passenger_count'],
                'bus_id' => $validated['bus_id'],
                'total_price' => $totalPrice,
                'discount_amount' => $discountAmount,
                'payment_status' => 'pending',
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'notes' => $validated['notes'],
            ]);

            // Note: Currently we are adjusting total_price directly.
            // If Midtrans requires item details, TourController currently passes the single total_price as 1 quantity.
            // So we'll pass the discounted total_price.

            return $booking;
        });

        return redirect()->route('tour.checkout', $booking);
    }

    public function checkout(TourBooking $booking)
    {
        if ($booking->user_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            abort(403);
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('dashboard')->with('info', 'Booking ini sudah lunas.');
        }

        // Auto-check status if pending
        if ($booking->payment_status === 'pending') {
            $payment = $booking->payments()->latest()->first();
            if ($payment) {
                app(\App\Http\Controllers\PaymentController::class)->checkStatus(request(), $payment->payable_id);
                $booking->refresh();
                if ($booking->payment_status === 'paid') {
                    return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil dikonfirmasi!');
                }
            }
        }

        $user = auth()->user();
        $snapToken = $booking->snap_token;

        if (!$snapToken) {
            $itemDetails = [
                [
                    'id' => $booking->booking_code,
                    'price' => (int) ($booking->total_price + $booking->discount_amount),
                    'quantity' => 1,
                    'name' => "Paket Wisata: {$booking->tourPackage->name}",
                ]
            ];

            if ($booking->discount_amount > 0) {
                $itemDetails[] = [
                    'id' => 'PROMO-' . $booking->booking_code,
                    'price' => -(int) $booking->discount_amount,
                    'quantity' => 1,
                    'name' => 'Diskon Promo',
                ];
            }

            $params = $this->midtrans->buildTransactionParams(
                $booking->booking_code,
                (int) $booking->total_price,
                $user->name,
                $user->email,
                $user->phone ?? '',
                $itemDetails
            );

            $snapToken = $this->midtrans->createSnapToken($params);

            if ($snapToken) {
                $booking->update(['snap_token' => $snapToken]);

                Payment::updateOrCreate(
                    ['midtrans_order_id' => $booking->booking_code],
                    [
                        'payable_type' => TourBooking::class,
                        'payable_id' => $booking->id,
                        'amount' => $booking->total_price,
                        'status' => 'pending',
                        'snap_token' => $snapToken,
                    ]
                );
            }
        }

        return view('tour.checkout', [
            'booking' => $booking,
            'snapToken' => $snapToken,
            'clientKey' => config('midtrans.client_key'),
            'snapUrl' => config('midtrans.snap_url'),
        ]);
    }
}
