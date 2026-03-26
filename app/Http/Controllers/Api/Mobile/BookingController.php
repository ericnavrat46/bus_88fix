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
            ->join('schedules','bookings.schedule_id','=','schedules.id')
            ->join('routes','schedules.route_id','=','routes.id')
            ->join('buses','schedules.bus_id','=','buses.id')
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
                'routes.origin',
                'routes.destination',
                'buses.name as bus_name'
            )
            ->where('bookings.user_id',$user_id)
            ->orderBy('bookings.created_at','desc')
            ->get();

        // Tambahkan data passengers ke setiap booking
        $bookings = $bookings->map(function ($booking) {
            $booking->passengers = DB::table('booking_passengers')
                ->where('booking_id', $booking->id)
                ->select('seat_number as seat', 'passenger_name', 'phone')
                ->get()
                ->toArray();
            return $booking;
        });

        return response()->json([
            'status' => true,
            'data' => $bookings
        ]);
    }


    // ==============================
    // DETAIL BOOKING
    // ==============================
    public function bookingDetail($booking_id)
    {

        $booking = DB::table('bookings')
            ->join('schedules','bookings.schedule_id','=','schedules.id')
            ->join('routes','schedules.route_id','=','routes.id')
            ->join('buses','schedules.bus_id','=','buses.id')
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
                'buses.name as bus_name'
            )
            ->where('bookings.id',$booking_id)
            ->first();

        $passengers = DB::table('booking_passengers')
            ->where('booking_id',$booking_id)
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
    // UPLOAD BUKTI PEMBAYARAN (FIX)
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

        return response()->json([
            'status' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'path' => $path
        ]);
    }

}