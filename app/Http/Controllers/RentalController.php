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
            'destination' => $validated['destination'],
            'purpose' => $validated['purpose'] ?? null,
            'passenger_count' => $validated['passenger_count'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'approval_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Permintaan sewa bus berhasil dikirim! Menunggu persetujuan admin.');
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

        $user = auth()->user();
        $rentalCode = $rental->rental_code;

        $params = $this->midtrans->buildTransactionParams(
            $rentalCode,
            (int) $rental->total_price,
            $user->name,
            $user->email,
            $user->phone ?? '',
            [[
                'id' => $rentalCode,
                'price' => (int) $rental->total_price,
                'quantity' => 1,
                'name' => "Sewa Bus - {$rental->destination} ({$rental->duration_days} hari)",
            ]]
        );

        $snapToken = $this->midtrans->createSnapToken($params);

        if ($snapToken) {
            $rental->update([
                'snap_token' => $snapToken,
                'midtrans_order_id' => $rentalCode,
                'payment_status' => 'pending',
            ]);

            Payment::create([
                'payable_type' => Rental::class,
                'payable_id' => $rental->id,
                'midtrans_order_id' => $rentalCode,
                'amount' => $rental->total_price,
                'status' => 'pending',
                'snap_token' => $snapToken,
            ]);
        }

        return view('rental.checkout', [
            'rental' => $rental,
            'snapToken' => $snapToken,
            'clientKey' => config('midtrans.client_key'),
            'snapUrl' => config('midtrans.snap_url'),
        ]);
    }
}
