<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Payment;
use App\Models\Schedule;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Show seat selection page
     */
    public function selectSeat(Schedule $schedule)
    {
        $schedule->load(['bus', 'route']);
        $bookedSeats = $schedule->booked_seats;

        return view('booking.select-seat', compact('schedule', 'bookedSeats'));
    }

    /**
     * Show passenger form
     */
    public function passengerForm(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'seats' => 'required|array|min:1',
            'seats.*' => 'required|string',
        ]);

        $schedule->load(['bus', 'route']);

        return view('booking.passenger-form', [
            'schedule' => $schedule,
            'selectedSeats' => $validated['seats'],
        ]);
    }

    /**
     * Process booking and create Midtrans payment
     */
    public function store(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'passengers' => 'required|array|min:1',
            'passengers.*.seat_number' => 'required|string',
            'passengers.*.passenger_name' => 'required|string|max:255',
            'passengers.*.id_number' => 'nullable|string|max:30',
            'passengers.*.phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $schedule->load(['bus', 'route']);
        $user = auth()->user();

        try {
            $booking = DB::transaction(function () use ($validated, $schedule, $user) {
                $totalSeats = count($validated['passengers']);
                $totalPrice = $schedule->price * $totalSeats;
                $bookingCode = Booking::generateBookingCode();

                $booking = Booking::create([
                    'booking_code' => $bookingCode,
                    'user_id' => $user->id,
                    'schedule_id' => $schedule->id,
                    'total_seats' => $totalSeats,
                    'total_price' => $totalPrice,
                    'payment_status' => 'pending',
                    'midtrans_order_id' => $bookingCode,
                    'expired_at' => now()->addHours(2),
                ]);

                foreach ($validated['passengers'] as $passenger) {
                    BookingPassenger::create([
                        'booking_id' => $booking->id,
                        'seat_number' => $passenger['seat_number'],
                        'passenger_name' => $passenger['passenger_name'],
                        'id_number' => $passenger['id_number'] ?? null,
                        'phone' => $passenger['phone'] ?? null,
                    ]);
                }

                // Create Midtrans Snap Token
                $itemDetails = [];
                foreach ($validated['passengers'] as $passenger) {
                    $itemDetails[] = [
                        'id' => 'SEAT-' . $passenger['seat_number'],
                        'price' => (int) $schedule->price,
                        'quantity' => 1,
                        'name' => "Kursi {$passenger['seat_number']} - {$schedule->route->origin} ke {$schedule->route->destination}",
                    ];
                }

                $params = $this->midtrans->buildTransactionParams(
                    $bookingCode,
                    (int) $totalPrice,
                    $user->name,
                    $user->email,
                    $user->phone ?? '',
                    $itemDetails
                );

                $snapToken = $this->midtrans->createSnapToken($params);

                if ($snapToken) {
                    $booking->update(['snap_token' => $snapToken]);

                    Payment::create([
                        'payable_type' => Booking::class,
                        'payable_id' => $booking->id,
                        'midtrans_order_id' => $bookingCode,
                        'amount' => $totalPrice,
                        'status' => 'pending',
                        'snap_token' => $snapToken,
                    ]);
                }

                return $booking;
            });

            return redirect()->route('booking.checkout', $booking);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show checkout page with Midtrans Snap
     */
    public function checkout(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['schedule.bus', 'schedule.route', 'passengers']);

        return view('booking.checkout', [
            'booking' => $booking,
            'snapToken' => $booking->snap_token,
            'clientKey' => config('midtrans.client_key'),
            'snapUrl' => config('midtrans.snap_url'),
        ]);
    }
}
