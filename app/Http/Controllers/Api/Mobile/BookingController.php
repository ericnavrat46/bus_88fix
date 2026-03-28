<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    /// 🔥 LIST BOOKING USER
    public function myBookings($user_id)
    {
        $bookings = DB::table('bookings')
            ->join('users','bookings.user_id','=','users.id')
            ->join('schedules','bookings.schedule_id','=','schedules.id')
            ->join('routes','schedules.route_id','=','routes.id')
            ->join('buses','schedules.bus_id','=','buses.id')

            ->select(
                'bookings.*',

                'users.name',
                'users.email',
                'users.phone',

                'routes.origin',
                'routes.destination',

                'schedules.departure_date',
                'schedules.departure_time',
                'schedules.arrival_time',

                'buses.name as bus_name',
                'buses.type as bus_type'
            )

            ->where('bookings.user_id',$user_id)
            ->orderBy('bookings.created_at','desc')
            ->get();

        foreach ($bookings as $b) {

            $status = 'pending_payment';

            /// ❌ CANCEL
            if ($b->payment_status == 'cancelled') {
                $status = 'cancelled';
            }

            elseif ($b->payment_status == 'refunded') {
                $status = 'cancelled';
            }

            /// ⏰ EXPIRED (optional)
            elseif ($b->payment_status == 'expired') {
                $status = 'expired';
            }

            /// 💰 SUDAH BAYAR (MIDTRANS / ADMIN)
            elseif ($b->payment_status == 'paid') {

                if (now()->gt($b->departure_date)) {
                    $status = 'completed'; // masuk riwayat
                } else {
                    $status = 'paid'; // masih pesanan
                }
            }

            /// 🟡 PENDING (BELUM BAYAR ATAU SUDAH UPLOAD)
            elseif ($b->payment_status == 'pending') {

                /// 🔥 BEDAIN KONDISI
                if ($b->payment_proof) {
                    $status = 'waiting_confirmation'; // sudah upload
                } else {
                    $status = 'pending_payment'; // belum bayar
                }
            }

            $b->status_final = $status;
        }

        return response()->json([
            'status' => true,
            'data' => $bookings
        ]);
    }


    /// 🔥 DETAIL BOOKING
    public function bookingDetail($booking_id)
    {
        $booking = DB::table('bookings')
            ->join('users','bookings.user_id','=','users.id')
            ->join('schedules','bookings.schedule_id','=','schedules.id')
            ->join('routes','schedules.route_id','=','routes.id')
            ->select(
                'bookings.*',

                'users.name',
                'users.email',
                'users.phone',

                'routes.origin',
                'routes.destination',

                'schedules.departure_date',
                'schedules.departure_time'
            )
            ->where('bookings.id',$booking_id)
            ->first();

        /// 🔥 STATUS FINAL DI DETAIL
        if ($booking) {

            $status = 'pending_payment';

            if ($booking->payment_status == 'cancelled') {
                $status = 'cancelled';
            }

            elseif ($booking->payment_status == 'expired') {
                $status = 'expired';
            }

           elseif ($booking->payment_status == 'paid') {

            if (now()->gt(\Carbon\Carbon::parse($booking->departure_date)->addDay())) {
                $status = 'completed';
            } else {
                $status = 'paid';
            }
}

            elseif ($booking->payment_status == 'pending') {

                if ($booking->payment_proof) {
                    $status = 'waiting_confirmation';
                } else {
                    $status = 'pending_payment';
                }
            }

            $booking->status_final = $status;
        }

        $seats = DB::table('booking_passengers')
            ->where('booking_id',$booking_id)
            ->pluck('seat_number');

        return response()->json([
            'status' => true,
            'data' => [
                'booking' => $booking,
                'seats' => $seats
            ]
        ]);
    }


    /// 🔥 UPLOAD BUKTI PEMBAYARAN
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

        DB::table('bookings')
            ->where('id', $booking->id)
            ->update([
                'payment_proof' => $path,
                'payment_status' => 'pending', // 🔥 tetap pending
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'path' => $path
        ]);
    }


    /// ❌ CANCEL BOOKING
    public function cancel($id)
    {
        $booking = DB::table('bookings')->where('id', $id)->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking tidak ditemukan'
            ]);
        }

        /// ❌ tidak boleh cancel kalau sudah bayar
        if ($booking->payment_status == 'paid') {
            return response()->json([
                'status' => false,
                'message' => 'Sudah dibayar, hubungi admin'
            ]);
        }

        DB::table('bookings')
            ->where('id', $id)
            ->update([
                'payment_status' => 'cancelled',
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Booking berhasil dibatalkan'
        ]);
    }

    public function finish($id)
    {
        DB::table('bookings')
            ->where('id', $id)
            ->update([
                'departure_date' => now()->subDay(), // 🔥 paksa completed
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Pesanan selesai'
        ]);
    }
}
