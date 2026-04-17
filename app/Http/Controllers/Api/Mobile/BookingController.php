<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    // ==============================
    // GET PESANAN USER
    // ==============================
    public function myBookings($user_id)
    {
        $bookings = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->join('buses', 'schedules.bus_id', '=', 'buses.id')
            ->select(
                'bookings.id',
                'bookings.booking_code',
                'bookings.total_price',
                'bookings.total_seats',
                'bookings.payment_status',
                'bookings.payment_proof',
                'bookings.expired_at',
                'schedules.departure_date',
                'schedules.departure_time',
                'schedules.arrival_time',
                'routes.origin',
                'routes.destination',
                'buses.name as bus_name',
                'users.email',
                'users.phone'
            )
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.user_id', $user_id)
            ->orderBy('bookings.created_at', 'desc')
            ->get();

        $bookings = $bookings->map(function ($booking) {

            $booking->passengers = DB::table('booking_passengers')
                ->where('booking_id', $booking->id)
                ->select('seat_number as seat', 'passenger_name', 'phone')
                ->get()
                ->toArray();

            $status = 'pending_payment';

            if ($booking->payment_status == 'cancelled') {
                $status = 'cancelled';
            } elseif ($booking->payment_status == 'expired') {
                $status = 'expired';
            } elseif ($booking->payment_status == 'paid') {
                if (now()->gt(\Carbon\Carbon::parse($booking->departure_date)->addDay())) {
                    $status = 'completed';
                } else {
                    $status = 'paid';
                }
            } elseif ($booking->payment_status == 'pending') {
                if ($booking->payment_proof) {
                    $status = 'waiting_confirmation';
                } else {
                    if ($booking->expired_at && now()->gt(\Carbon\Carbon::parse($booking->expired_at))) {
                        $status = 'expired';
                    } else {
                        $status = 'pending_payment';
                    }
                }
            }

            $arr = (array) $booking;
            $arr['status_final'] = $status;
            return $arr;
        });

        return response()->json([
            'status' => true,
            'data' => $bookings->values()
        ]);
    }


    // ==============================
    // DETAIL BOOKING
    // ==============================
    public function bookingDetail($booking_id)
    {
        $booking = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->join('buses', 'schedules.bus_id', '=', 'buses.id')
            ->select(
                'bookings.id',
                'bookings.booking_code',
                'bookings.total_price',
                'bookings.total_seats',
                'bookings.payment_status',
                'bookings.payment_proof',
                'bookings.expired_at',
                'routes.origin',
                'routes.destination',
                'schedules.departure_date',
                'schedules.departure_time',
                'schedules.arrival_time',
                'buses.name as bus_name',
                'users.email',
                'users.phone'
            )
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.id', $booking_id)
            ->first();

        $passengers = DB::table('booking_passengers')
            ->where('booking_id', $booking_id)
            ->select('seat_number as seat', 'passenger_name', 'phone')
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'booking' => $booking,
                'seats' => $passengers->pluck('seat'),
                'passengers' => $passengers
            ]
        ]);
    }


    // ==============================
    // UPLOAD BUKTI PEMBAYARAN
    // ==============================
    public function uploadPayment(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $booking = null;

        if ($request->booking_id && $request->booking_id != 0) {
            $booking = DB::table('bookings')->where('id', $request->booking_id)->first();
        }

        if (!$booking && $request->booking_code) {
            $booking = DB::table('bookings')->where('booking_code', $request->booking_code)->first();
        }

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        $file = $request->file('payment_proof');
        $path = $file->store('payment_proofs/bookings', 'public');

        $updated = DB::table('bookings')
            ->where('id', $booking->id)
            ->update([
                'payment_proof' => $path,
                'payment_status' => 'pending',
                'updated_at' => now()
            ]);

        if (!$updated) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal update booking'
            ]);
        }

        // 🔥 NOTIF KE ADMIN — ada bukti bayar masuk
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            \App\Helpers\NotificationHelper::send(
                $admin->id,
                'Bukti Pembayaran Masuk 💳',
                'Ada bukti bayar baru dari booking ' . $booking->booking_code,
                'payment_proof'
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'path' => $path
        ]);
    }

    // ==============================
    // CANCEL BOOKING
    // ==============================
    public function cancel($id)
    {
        DB::table('bookings')
            ->where('id', $id)
            ->update([
                'payment_status' => 'cancelled',
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking dibatalkan'
        ]);
    }

    // ==============================
    // FINISH BOOKING
    // ==============================
    public function finish($id)
    {
        DB::table('bookings')
            ->where('id', $id)
            ->update([
                'departure_date' => now()->subDay(),
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Selesai'
        ]);
    }

    // ==============================
    // 🔥 CONFIRM PAYMENT (BARU)
    // ==============================
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:paid,cancelled'
        ]);

        $booking = DB::table('bookings')->where('id', $request->id)->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        DB::table('bookings')
            ->where('id', $request->id)
            ->update([
                'payment_status' => $request->status,
                'updated_at' => now()
            ]);

        // 🔥 NOTIF KE USER — kasih tahu hasil konfirmasi
        $title = $request->status == 'paid'
            ? 'Pembayaran Dikonfirmasi ✅'
            : 'Pembayaran Ditolak ❌';

        $body = $request->status == 'paid'
            ? 'Pembayaran booking ' . $booking->booking_code . ' telah dikonfirmasi. Selamat menikmati perjalanan!'
            : 'Maaf, pembayaran booking ' . $booking->booking_code . ' ditolak. Silakan hubungi admin.';

        \App\Helpers\NotificationHelper::send(
            $booking->user_id,
            $title,
            $body,
            'payment_status'
        );

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diupdate'
        ]);
    }
}