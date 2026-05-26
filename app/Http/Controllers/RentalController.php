<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function index()
    {
        $buses = Bus::where('status', 'active')->get();
        return view('rental.index', compact('buses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'nullable|exists:buses,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'destination' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'passenger_count' => 'nullable|integer|min:1',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
        ]);

        $user = auth()->user();
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $durationDays = $startDate->diffInDays($endDate) + 1;

        $rental = Rental::create([
            'rental_code' => Rental::generateRentalCode(),
            'user_id' => $user->id,
            'bus_id' => $validated['bus_id'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration_days' => $durationDays,
            'pickup_location' => $validated['pickup_location'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'destination' => $validated['destination'],
            'purpose' => $validated['purpose'] ?? null,
            'passenger_count' => $validated['passenger_count'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'approval_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Kirim notifikasi ke semua admin
        $adminUsers = \App\Models\User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Sewa Bus Baru',
                'message' => "Ada pengajuan sewa bus baru dari {$user->name} untuk tujuan {$rental->destination}.",
                'type' => 'rental',
                'data' => json_encode(['rental_id' => $rental->id, 'rental_code' => $rental->rental_code])
            ]);
        }

        return back()->with('rental_success', [
            'code' => $rental->rental_code,
            'destination' => $rental->destination,
            'start_date' => $startDate->translatedFormat('d F Y'),
            'duration' => $durationDays,
            'purpose' => $rental->purpose
        ]);
    }

    /**
     * Pay approved rental
     */
    public function pay(Rental $rental)
    {
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        if ($rental->approval_status !== 'approved' || !$rental->total_price) {
            return back()->withErrors(['error' => 'Rental belum disetujui atau harga belum ditentukan.']);
        }

        // Auto-check status if pending
        if ($rental->payment_status === 'pending') {
            $payment = $rental->payments()->latest()->first();
            if ($payment) {
                app(\App\Http\Controllers\PaymentController::class)->checkStatus(request(), $payment);
                $rental->refresh();
                if ($rental->payment_status === 'paid') {
                    return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil dikonfirmasi!');
                }
            }
        }

        $user = auth()->user();
        $rentalCode = $rental->rental_code;

        $snapToken = $rental->snap_token;

        if (!$snapToken) {
            $subtotal = $rental->total_price;
            $discount = $rental->discount_amount ?? 0;
            $finalPrice = $subtotal - $discount;

            $itemDetails = [
                [
                    'id' => $rentalCode,
                    'price' => (int) $subtotal,
                    'quantity' => 1,
                    'name' => "Sewa Bus - {$rental->destination} ({$rental->duration_days} hari)",
                ]
            ];

            if ($discount > 0) {
                $itemDetails[] = [
                    'id' => 'DISC-' . $rentalCode,
                    'price' => -(int) $discount,
                    'quantity' => 1,
                    'name' => 'Diskon Promo',
                ];
            }

            $params = $this->midtrans->buildTransactionParams(
                $rentalCode,
                (int) $finalPrice,
                $user->name,
                $user->email,
                $user->phone ?? '',
                $itemDetails
            );

            $snapToken = $this->midtrans->createSnapToken($params);

            if ($snapToken) {
                $rental->update([
                    'snap_token' => $snapToken,
                    'midtrans_order_id' => $rentalCode,
                    'payment_status' => 'pending',
                ]);

                Payment::updateOrCreate(
                    ['midtrans_order_id' => $rentalCode],
                    [
                        'payable_type' => Rental::class,
                        'payable_id' => $rental->id,
                        'amount' => $finalPrice,
                        'status' => 'pending',
                        'snap_token' => $snapToken,
                    ]
                );
            }
        }

        return view('rental.checkout', [
            'rental' => $rental,
            'snapToken' => $snapToken,
            'clientKey' => config('midtrans.client_key'),
            'snapUrl' => config('midtrans.snap_url'),
        ]);
    }
    public function applyPromo(Request $request, Rental $rental)
    {
        if ($rental->user_id !== auth()->id()) {
            return response()->json(['valid' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'promo_code' => 'required|string',
        ]);

        $promo = \App\Models\PromoBanner::active()
            ->where('promo_code', strtoupper($request->promo_code))
            ->first();

        if (!$promo || !$promo->isValidFor('rental')) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak ditemukan atau tidak berlaku untuk layanan ini.'
            ]);
        }

        if ($promo->min_transaction && $rental->total_price < $promo->min_transaction) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimal transaksi untuk promo ini adalah Rp ' . number_format($promo->min_transaction, 0, ',', '.')
            ]);
        }

        $discountAmount = $promo->calculateDiscount($rental->total_price);
        $finalPrice = $rental->total_price - $discountAmount;

        // Update Rental
        $rental->update([
            'promo_banner_id' => $promo->id,
            'discount_amount' => $discountAmount,
            // We keep the original total_price set by admin, but we need to pass the final price to Midtrans.
            // Actually, it's better to store the final price separately or use the discount field.
            'snap_token' => null, // Reset snap token so it regenerates with new price
        ]);

        $promo->increment('used_quota');

        return response()->json([
            'valid' => true,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'message' => 'Promo berhasil digunakan!'
        ]);
    }
}
