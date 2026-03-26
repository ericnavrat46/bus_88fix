<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatController extends Controller
{
    // =============================
    // GET SEAT LAYOUT
    // =============================
    public function getSeatLayout($schedule_id)
    {
        $schedule = DB::table('schedules')->where('id', $schedule_id)->first();

        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule tidak ditemukan'
            ], 404);
        }

        $bus = DB::table('buses')->where('id', $schedule->bus_id)->first();

        // ✅ FIX: Jika tanggal perjalanan sudah lewat, kembalikan kursi kosong
        if ($schedule->departure_date < now()->toDateString()) {
            return response()->json([
                'capacity' => $bus->capacity,
                'booked_seats' => []
            ]);
        }

        // ✅ FIX: hanya ambil kursi yang pending & paid
        $bookedSeats = DB::table('booking_passengers')
            ->join('bookings', 'booking_passengers.booking_id', '=', 'bookings.id')
            ->where('bookings.schedule_id', $schedule_id)
            ->whereIn('bookings.payment_status', ['pending', 'paid'])
            ->pluck('booking_passengers.seat_number');

        return response()->json([
            'capacity' => $bus->capacity,
            'booked_seats' => $bookedSeats
        ]);
    }


    // =============================
    // BOOK SEATS
    // =============================
    public function bookSeats(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'schedule_id' => 'required',
            'seats' => 'required|array',
            'passenger_name' => 'required'
        ]);

        $user_id = $request->user_id;
        $schedule_id = $request->schedule_id;
        $seats = $request->seats;
        $passenger_name = $request->passenger_name;
        $phone = $request->phone;

        // =============================
        // MAX 5 SEATS
        // =============================
        if (count($seats) > 5) {
            return response()->json([
                'message' => 'Maksimal booking 5 kursi'
            ], 400);
        }

        // =============================
        // GET SCHEDULE
        // =============================
        $schedule = DB::table('schedules')->where('id', $schedule_id)->first();

        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule tidak ditemukan'
            ], 404);
        }

        // ✅ FIX: Cek jadwal sudah lewat, tidak bisa booking
        if ($schedule->departure_date < now()->toDateString()) {
            return response()->json([
                'message' => 'Jadwal ini sudah selesai, tidak bisa booking'
            ], 400);
        }

        // =============================
        // CEK KURSI SUDAH DIBOOKING
        // =============================
        foreach ($seats as $seat) {

            $exists = DB::table('booking_passengers')
                ->join('bookings', 'booking_passengers.booking_id', '=', 'bookings.id')
                ->where('bookings.schedule_id', $schedule_id)
                ->where('booking_passengers.seat_number', $seat)
                ->whereIn('bookings.payment_status', ['pending', 'paid'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => "Kursi $seat sudah dibooking"
                ], 400);
            }
        }

        // =============================
        // HITUNG TOTAL
        // =============================
        $totalSeats = count($seats);
        $totalPrice = $schedule->price * $totalSeats;

        // =============================
        // BUAT BOOKING
        // =============================
        $expiredAt = now()->addHour(); // ← batas waktu 1 jam

        $bookingId = DB::table('bookings')->insertGetId([
            'booking_code'   => 'BK-' . time(),
            'user_id'        => $user_id,
            'schedule_id'    => $schedule_id,
            'total_seats'    => $totalSeats,
            'total_price'    => $totalPrice,
            'payment_status' => 'pending',
            'expired_at'     => $expiredAt,  // ← tambah ini
            'created_at'     => now(),
            'updated_at'     => now()
        ]);

        // =============================
        // SIMPAN PENUMPANG
        // =============================
        $passengers = $request->passengers ?? [];

        foreach ($seats as $index => $seat) {
            $passengerData = $passengers[$index] ?? [];

            DB::table('booking_passengers')->insert([
                'booking_id'     => $bookingId,
                'seat_number'    => $seat,
                'passenger_name' => $passengerData['passenger_name'] ?? $passenger_name,
                'phone'          => $passengerData['phone'] ?? $phone,
                'created_at'     => now(),
                'updated_at'     => now()
            ]);
        }

        // =============================
        // RETURN RESPONSE
        // =============================
        $route = DB::table('routes')
            ->join('schedules', 'schedules.route_id', '=', 'routes.id')
            ->join('buses', 'schedules.bus_id', '=', 'buses.id')
            ->where('schedules.id', $schedule_id)
            ->select(
                'routes.origin',
                'routes.destination',
                'schedules.departure_date',
                'schedules.departure_time',
                'buses.name as bus_name'
            )
            ->first();

        return response()->json([
            'message' => 'Booking kursi berhasil',
            'data' => [
                'booking_id'     => $bookingId,
                'booking_code'   => 'BK-' . time(),
                'total_price'    => $totalPrice,
                'total_seats'    => $totalSeats,
                'seats'          => $seats,
                'origin'         => $route->origin ?? '',
                'destination'    => $route->destination ?? '',
                'departure_date' => $route->departure_date ?? '',
                'departure_time' => $route->departure_time ?? '',
                'bus_name'       => $route->bus_name ?? '',
                'expired_at'     => $expiredAt,  // ← tambah ini
            ]
        ]);
    }


    // =============================
    // GET SCHEDULES
    // =============================
    public function getSchedules()
    {
        $schedules = DB::table('schedules')
            ->join('buses', 'schedules.bus_id', '=', 'buses.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->select(
                'schedules.id',
                'routes.origin',
                'routes.destination',
                'schedules.departure_date',
                'schedules.departure_time',
                'schedules.arrival_time',
                'schedules.price',
                'buses.name as bus_name',
                'buses.capacity'
            )
            ->orderBy('schedules.departure_time', 'asc')
            ->get();

        return response()->json($schedules);
    }
}