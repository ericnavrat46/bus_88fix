<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Refund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    /**
     * CHECK REFUND
     */
    public function checkRefund(Request $request, $bookingId)
    {
        $booking = Booking::with('schedule')->find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        // cek pemilik booking
        $userId = $request->user_id;

        if ($booking->user_id != $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // cek status refund
        if (
            $booking->payment_status == 'refund' ||
            $booking->payment_status == 'pending_refund'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Refund sudah diajukan'
            ]);
        }

        // gabungkan tanggal + jam keberangkatan
        $departure = Carbon::parse(
            $booking->schedule->departure_date .
            ' ' .
            $booking->schedule->departure_time
        );

        // hitung selisih jam
        $hoursDiff = now()->diffInHours($departure, false);

        // refund tidak tersedia
        if ($hoursDiff < 6) {
            return response()->json([
                'success' => false,
                'message' => 'Refund tidak tersedia kurang dari 6 jam sebelum keberangkatan'
            ]);
        }

        // hitung persen refund
        if ($hoursDiff >= 24) {
            $refundPercentage = 90;
        } else {
            $refundPercentage = 70;
        }

        // nominal refund
        $refundAmount = ($booking->total_price * $refundPercentage) / 100;

        return response()->json([
            'success' => true,
            'refund_percentage' => $refundPercentage,
            'refund_amount' => $refundAmount,
            'hours_left' => $hoursDiff,
            'booking_id' => $booking->id,
        ]);
    }

    /**
     * SUBMIT REFUND
     */
    public function submitRefund(Request $request, $bookingId)
    {
        $booking = Booking::with('schedule')->find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        // cek pemilik booking
        if ($booking->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // validasi input
        $request->validate([
            'reason' => 'required|string|min:10',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        // cek waktu keberangkatan
        $departure = Carbon::parse(
            $booking->schedule->departure_date .
            ' ' .
            $booking->schedule->departure_time
        );

        $hoursDiff = now()->diffInHours($departure, false);

        if ($hoursDiff < 6) {
            return response()->json([
                'success' => false,
                'message' => 'Refund tidak tersedia'
            ]);
        }

        // hitung refund
        if ($hoursDiff >= 24) {
            $refundPercentage = 90;
        } else {
            $refundPercentage = 70;
        }

        $refundAmount = ($booking->total_price * $refundPercentage) / 100;

        // simpan refund
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

        // update booking
        $booking->update([
            'payment_status' => 'pending_refund'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Refund berhasil diajukan'
        ]);
    }
}