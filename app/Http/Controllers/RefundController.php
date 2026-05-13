<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Refund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    /**
     * Show refund request form
     */
    public function create(Booking $booking)
    {
        // Check ownership
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if already refunded or requested
        if ($booking->payment_status === 'refunded' || $booking->payment_status === 'pending_refund') {
            return redirect()->back()->with('error', 'Refund sudah diajukan atau sudah diproses.');
        }

        // Check policy
        $departure = Carbon::parse($booking->schedule->departure_date->format('Y-m-d') . ' ' . $booking->schedule->departure_time);
        $hoursDiff = now()->diffInHours($departure, false);

        if ($hoursDiff < 6) {
            return redirect()->back()->with('error', 'Refund tidak diizinkan kurang dari 6 jam sebelum keberangkatan.');
        }

        if ($hoursDiff >= 24) {
            $refundPercentage = 90;
        } else {
            $refundPercentage = 70;
        }

        $refundAmount = ($booking->total_price * $refundPercentage) / 100;

        return view('dashboard.refund-request', compact('booking', 'refundAmount', 'refundPercentage'));
    }

    /**
     * Store refund request
     */
    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        $request->validate([
            'reason' => 'required|string|min:10',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        if ($booking->payment_status === 'pending_refund') {
            return redirect()->back()->with('error', 'Refund sedang dalam proses verifikasi.');
        }

        $departure = Carbon::parse($booking->schedule->departure_date->format('Y-m-d') . ' ' . $booking->schedule->departure_time);
        $hoursDiff = now()->diffInHours($departure, false);

        if ($hoursDiff < 6) {
            return redirect()->back()->with('error', 'Batas waktu refund (minimal 6 jam sebelum berangkat) telah habis.');
        }

        if ($hoursDiff >= 24) {
            $refundPercentage = 90;
        } else {
            $refundPercentage = 70;
        }

        $refundAmount = ($booking->total_price * $refundPercentage) / 100;

        Refund::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'refund_amount' => $refundAmount,
            'reason' => $request->reason,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'status' => 'pending',
        ]);

        // Update booking status to pending_refund so it cannot be used/printed
        $booking->update(['payment_status' => 'pending_refund']);

        return redirect()->route('dashboard.booking', $booking)->with('success', 'Permintaan refund berhasil dikirim. Tiket Anda kini dalam status "Menunggu Verifikasi Refund".');
    }

    /**
     * Admin: List refund requests
     */
    public function adminIndex()
    {
        $refunds = Refund::with(['booking', 'user'])->latest()->paginate(10);
        return view('admin.transactions.refunds', compact('refunds'));
    }

    /**
     * Admin: Edit refund request (separate page)
     */
    public function adminEdit(Refund $refund)
    {
        return view('admin.transactions.refund-edit', compact('refund'));
    }

    /**
     * Admin: Process refund
     */
    public function adminAction(Request $request, Refund $refund)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'admin_notes' => 'nullable|string'
        ]);

        $refund->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
        ]);

        if ($request->status === 'completed') {
            $refund->booking->update(['payment_status' => 'refunded']);
        }

        return redirect()->back()->with('success', 'Status refund berhasil diupdate.');
    }
}
