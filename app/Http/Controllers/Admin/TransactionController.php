<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\TourBooking;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function bookings()
    {
        $bookings = Booking::with(['user', 'schedule.route', 'schedule.bus'])
            ->latest()
            ->paginate(15);
        
        $this->syncListPending($bookings);

        return view('admin.transactions.bookings', compact('bookings'));
    }

    public function rentals()
    {
        $rentals = Rental::with(['user', 'bus'])
            ->latest()
            ->paginate(15);

        $this->syncListPending($rentals);

        return view('admin.transactions.rentals', compact('rentals'));
    }

    public function tours()
    {
        $bookings = TourBooking::with(['user', 'tourPackage'])
            ->latest()
            ->paginate(15);

        $this->syncListPending($bookings);

        return view('admin.transactions.tours', compact('bookings'));
    }

    protected function syncListPending($items)
    {
        $midtrans = app(\App\Services\MidtransService::class);
        foreach ($items as $item) {
            if ($item->payment_status === 'pending' || $item->payment_status === 'unpaid') {
                $orderId = $item->booking_code ?? $item->rental_code ?? null;
                if ($orderId) {
                    $statusData = $midtrans->getTransactionStatus($orderId);
                    if ($statusData) {
                        $rawStatus = $statusData['transaction_status'] ?? 'pending';
                        if (in_array($rawStatus, ['settlement', 'capture', 'success'])) {
                            $item->update([
                                'payment_status' => 'paid',
                                'paid_at' => now()
                            ]);
                            
                            // Also update the payment record if exists
                            Payment::where('midtrans_order_id', $orderId)->update(['status' => 'settlement']);
                        } elseif (in_array($rawStatus, ['expire', 'cancel', 'deny'])) {
                            $item->update(['payment_status' => ($rawStatus === 'expire' ? 'expired' : 'cancelled')]);
                            Payment::where('midtrans_order_id', $orderId)->update(['status' => $rawStatus]);
                        }
                    }
                }
            }
        }
    }

    public function tourShow(TourBooking $booking)
    {
        $booking->load(['user', 'tourPackage', 'payments']);

        return view('admin.transactions.tour-detail', compact('booking'));
    }

    public function approveRental(Rental $rental, Request $request)
    {
        $validated = $request->validate([
            'total_price' => 'required|numeric|min:0',
            'bus_id' => 'required|exists:buses,id',
            'admin_notes' => 'nullable|string',
        ]);

        $rental->update([
            'approval_status' => 'approved',
            'total_price' => $validated['total_price'],
            'bus_id' => $validated['bus_id'],
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        // Send Notification
        \App\Models\Notification::send(
            $rental->user_id,
            'Sewa Bus Disetujui!',
            "Permintaan sewa bus {$rental->rental_code} ({$rental->destination}) telah disetujui. Silakan selesaikan pembayaran.",
            'rental',
            ['rental_id' => $rental->id, 'rental_code' => $rental->rental_code]
        );

        return back()->with('success', 'Rental berhasil disetujui!');
    }

    public function rejectRental(Rental $rental, Request $request)
    {
        $rental->update([
            'approval_status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
        ]);

        // Send Notification
        \App\Models\Notification::send(
            $rental->user_id,
            'Sewa Bus Ditolak',
            "Maaf, permintaan sewa bus {$rental->rental_code} Anda ditolak. Alasan: " . ($request->input('admin_notes') ?? 'Tidak disebutkan.'),
            'rental',
            ['rental_id' => $rental->id, 'rental_code' => $rental->rental_code]
        );

        return back()->with('success', 'Rental ditolak.');
    }

    public function updateBookingStatus(Booking $booking, Request $request)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,expired,cancelled,refunded',
        ]);

        $booking->update([
            'payment_status' => $validated['payment_status'],
            'paid_at' => $validated['payment_status'] === 'paid' ? now() : $booking->paid_at,
        ]);

        return back()->with('success', 'Status booking berhasil diperbarui!');
    }

    public function approveManualBookingPayment(Booking $booking)
    {
        $booking->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        \App\Models\Notification::send(
            $booking->user_id,
            'Pembayaran Berhasil!',
            "Pembayaran manual untuk tiket bus {$booking->booking_code} telah dikonfirmasi oleh admin.",
            'booking',
            ['booking_id' => $booking->id, 'booking_code' => $booking->booking_code]
        );

        return back()->with('success', "Pembayaran manual untuk {$booking->booking_code} Berhasil Disetujui!");
    }

    public function approveManualRentalPayment(Rental $rental)
    {
        $rental->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        \App\Models\Notification::send(
            $rental->user_id,
            'Pembayaran Berhasil!',
            "Pembayaran manual untuk sewa bus {$rental->rental_code} telah dikonfirmasi oleh admin.",
            'rental',
            ['rental_id' => $rental->id, 'rental_code' => $rental->rental_code]
        );

        return back()->with('success', "Pembayaran manual untuk {$rental->rental_code} Berhasil Disetujui!");
    }

    public function approveManualTourPayment(TourBooking $booking)
    {
        $booking->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        \App\Models\Notification::send(
            $booking->user_id,
            'Pembayaran Berhasil!',
            "Pembayaran manual untuk paket wisata {$booking->booking_code} telah dikonfirmasi oleh admin.",
            'tour',
            ['booking_id' => $booking->id, 'booking_code' => $booking->booking_code]
        );

        return back()->with('success', "Pembayaran manual untuk {$booking->booking_code} Berhasil Disetujui!");
    }

    public function payments()
    {
        $payments = Payment::with('payable')
            ->latest()
            ->paginate(15);

        return view('admin.transactions.payments', compact('payments'));
    }
}
