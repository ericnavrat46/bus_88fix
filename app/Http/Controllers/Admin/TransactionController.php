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

        return view('admin.transactions.bookings', compact('bookings'));
    }

    public function rentals()
    {
        $rentals = Rental::with(['user', 'bus'])
            ->latest()
            ->paginate(15);

        return view('admin.transactions.rentals', compact('rentals'));
    }

    public function tours()
    {
        $bookings = TourBooking::with(['user', 'tourPackage'])
            ->latest()
            ->paginate(15);

        return view('admin.transactions.tours', compact('bookings'));
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

        return back()->with('success', 'Rental berhasil disetujui!');
    }

    public function rejectRental(Rental $rental, Request $request)
    {
        $rental->update([
            'approval_status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
        ]);

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

        return back()->with('success', "Pembayaran manual untuk {$booking->booking_code} Berhasil Disetujui!");
    }

    public function approveManualRentalPayment(Rental $rental)
    {
        $rental->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', "Pembayaran manual untuk {$rental->rental_code} Berhasil Disetujui!");
    }

    public function approveManualTourPayment(TourBooking $booking)
    {
        $booking->update([
            'payment_status' => 'paid',
        ]);

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
