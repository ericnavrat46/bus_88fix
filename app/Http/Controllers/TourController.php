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
        return view('tour.show', compact('package'));
    }

    public function bookingForm(TourPackage $package)
    {
        if ($package->status !== 'active') {
            abort(404);
        }
        return view('tour.booking', compact('package'));
    }

    public function storeBooking(Request $request, TourPackage $package)
    {
        $validated = $request->validate([
            'travel_date' => 'required|date|after:today',
            'passenger_count' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $finalPricePerPerson = $package->final_price;
        $totalPrice = $finalPricePerPerson * $validated['passenger_count'];
        $user = auth()->user();

        $booking = DB::transaction(function () use ($package, $validated, $totalPrice, $user) {
            $booking = TourBooking::create([
                'booking_code' => TourBooking::generateBookingCode(),
                'user_id' => $user->id,
                'tour_package_id' => $package->id,
                'travel_date' => $validated['travel_date'],
                'passenger_count' => $validated['passenger_count'],
                'total_price' => $totalPrice,
                'payment_status' => 'pending',
                'notes' => $validated['notes'],
            ]);

            // Increment Flash Sale Quota if active
            if ($flash = $package->active_flash_sale) {
                $flash->increment('used_quota');
            }

            return $booking;
        });

        return redirect()->route('tour.checkout', $booking);
    }

    public function checkout(TourBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('dashboard')->with('info', 'Booking ini sudah lunas.');
        }

        $user = auth()->user();
        $snapToken = $booking->snap_token;

        if (!$snapToken) {
            $params = $this->midtrans->buildTransactionParams(
                $booking->booking_code,
                (int) $booking->total_price,
                $user->name,
                $user->email,
                $user->phone ?? '',
                [
                    [
                        'id' => $booking->booking_code,
                        'price' => (int) $booking->total_price,
                        'quantity' => 1,
                        'name' => "Paket Wisata: {$booking->tourPackage->name}",
                    ]
                ]
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
