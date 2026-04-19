<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rental;
use App\Models\TourBooking;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $midtrans = app(\App\Services\MidtransService::class);
        $paymentController = app(\App\Http\Controllers\PaymentController::class);

        $bookings = Booking::with(['schedule.bus', 'schedule.route', 'passengers'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $rentals = Rental::with('bus')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $tourBookings = TourBooking::with('tourPackage')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Optional: Sync pending payments in background or just for few
        // For better UX, we sync pending ones
        $this->syncPendingPayments($bookings, $midtrans, $paymentController);
        $this->syncPendingPayments($rentals, $midtrans, $paymentController);
        $this->syncPendingPayments($tourBookings, $midtrans, $paymentController);

        return view('dashboard.index', compact('bookings', 'rentals', 'tourBookings'));
    }

    private function syncPendingPayments($items, $midtrans, $paymentController)
    {
        foreach ($items as $item) {
            if ($item->payment_status === 'pending' || $item->payment_status === 'unpaid') {
                $orderId = $item->booking_code ?? $item->rental_code ?? $item->midtrans_order_id;
                if ($orderId) {
                    $statusData = $midtrans->getTransactionStatus($orderId);
                    
                    // Only update if Midtrans API actually knows about this transaction (200, 201, 202)
                    $isValidResponse = $statusData && isset($statusData['status_code']) && in_array($statusData['status_code'], ['200', '201', '202']);
                    
                    if ($isValidResponse) {
                        $payment = Payment::where('midtrans_order_id', $orderId)->first();
                        $rawStatus = $statusData['transaction_status'] ?? 'pending';
                        
                        $status = match ($rawStatus) {
                            'settlement', 'capture', 'success' => 'settlement',
                            'pending' => 'pending',
                            'deny', 'cancel', 'expire' => 'expire',
                            default => $rawStatus,
                        };
                        
                        if ($payment && $payment->status !== $status) {
                            $payment->update(['status' => $status]);
                        }
                        
                        // Update main item status
                        if (in_array($status, ['settlement', 'capture', 'success'])) {
                            $item->update(['payment_status' => 'paid', 'paid_at' => now()]);
                        } elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
                            $item->update(['payment_status' => ($status === 'expire' ? 'expired' : 'cancelled')]);
                        }
                    }
                }
            }
        }
    }

    public function bookingDetail(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Auto update status if expired
        $booking->checkExpiration();

        $booking->load(['schedule.bus', 'schedule.route', 'passengers', 'latestPayment']);

        return view('dashboard.booking-detail', compact('booking'));
    }

    public function rentalDetail(Rental $rental)
    {
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        $rental->load(['bus', 'latestPayment']);

        return view('dashboard.rental-detail', compact('rental'));
    }

    public function tourDetail(TourBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['tourPackage', 'payments']);

        return view('dashboard.tour-detail', compact('booking'));
    }

    public function cancelBooking(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        if ($booking->payment_status === 'pending' || $booking->payment_status === 'unpaid') {
            $booking->update(['payment_status' => 'cancelled']);
            
            // Optional: Notify Admin
            \App\Models\Notification::send(
                1, // Assuming admin user ID is 1, adjust if needed
                'Pesanan Dibatalkan',
                "User {$booking->user->name} membatalkan pesanan tiket {$booking->booking_code}.",
                'booking',
                ['booking_id' => $booking->id]
            );

            return back()->with('success', 'Pesanan tiket berhasil dibatalkan.');
        }
        return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
    }

    public function cancelRental(Rental $rental)
    {
        if ($rental->user_id !== auth()->id()) abort(403);
        if ($rental->payment_status === 'pending' || $rental->payment_status === 'unpaid') {
            $rental->update(['payment_status' => 'cancelled']);
            
            \App\Models\Notification::send(
                1,
                'Sewa Bus Dibatalkan',
                "User {$rental->user->name} membatalkan sewa bus {$rental->rental_code}.",
                'rental',
                ['rental_id' => $rental->id]
            );

            return back()->with('success', 'Pesanan sewa bus berhasil dibatalkan.');
        }
        return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
    }

    public function cancelTour(TourBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        if ($booking->payment_status === 'pending' || $booking->payment_status === 'unpaid') {
            $booking->update(['payment_status' => 'cancelled']);
            
            \App\Models\Notification::send(
                1,
                'Tour Dibatalkan',
                "User {$booking->user->name} membatalkan pesanan tour {$booking->booking_code}.",
                'tour',
                ['booking_id' => $booking->id]
            );

            return back()->with('success', 'Pesanan tour berhasil dibatalkan.');
        }
        return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
    }
}
