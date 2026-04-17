<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TourBooking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TourBookingController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tour_package_id' => 'required|exists:tour_packages,id',
            'travel_date' => 'required|date',
            'passenger_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $booking = TourBooking::create([
            'booking_code' => 'TRX-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'user_id' => $request->user_id,
            'tour_package_id' => $request->tour_package_id,
            'travel_date' => $request->travel_date,
            'passenger_count' => $request->passenger_count,
            'total_price' => $request->total_price,
            'notes' => $request->notes,
            'payment_status' => 'pending',
            'payment_method' => null,
            'payment_proof' => null,
            'snap_token' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => $booking
        ]);
    }

    public function myBookings($user_id)
    {
        $data = DB::table('tour_bookings')
            ->join('users', 'tour_bookings.user_id', '=', 'users.id')
            ->join('tour_packages', 'tour_bookings.tour_package_id', '=', 'tour_packages.id')
            ->select(
                'tour_bookings.*',
                'users.name',
                'users.email',
                'users.phone',
                'tour_packages.name as package_name',
                'tour_packages.duration_days',
                DB::raw("CONCAT('" . url('/storage/') . "/', tour_packages.image) as image_url"),
                'tour_packages.destinations'
            )
            ->where('tour_bookings.user_id', $user_id)
            ->orderBy('tour_bookings.created_at', 'desc')
            ->get();

        $result = $data->map(function ($t) {

            $status = 'pending_payment';

            if ($t->payment_status == 'cancelled') {
                $status = 'cancelled';
            } elseif ($t->payment_status == 'expired') {
                $status = 'expired';
            } elseif ($t->payment_status == 'paid') {
                if (now()->gt(\Carbon\Carbon::parse($t->travel_date)->addDay())) {
                    $status = 'completed';
                } else {
                    $status = 'paid';
                }
            } elseif ($t->payment_status == 'pending') {
                if ($t->payment_proof) {
                    $status = 'waiting_confirmation';
                } else {
                    $status = 'pending_payment';
                }
            }

            $arr = (array) $t;
            $arr['status_final'] = $status;
            return $arr;
        });

        return response()->json([
            'success' => true,
            'data' => $result->values()
        ]);
    }

    public function cancel($id)
    {
        $booking = TourBooking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        if ($booking->payment_status == 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Sudah dibayar, hubungi admin'
            ]);
        }

        $booking->payment_status = 'cancelled';
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking dibatalkan'
        ]);
    }

    public function finish($id)
    {
        DB::table('tour_bookings')
            ->where('id', $id)
            ->update([
                'travel_date' => now()->subDay(),
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Selesai'
        ]);
    }

    public function uploadPayment(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $booking = TourBooking::find($request->id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $file = $request->file('payment_proof');
        $path = $file->store('payment_proofs/tours', 'public');

        $booking->payment_proof = $path;
        $booking->payment_status = 'pending';
        $booking->save();

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
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'path' => $path
        ]);
    }

    // 🔥 FUNGSI BARU — Admin konfirmasi / tolak pembayaran
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:paid,cancelled'
        ]);

        $booking = TourBooking::find($request->id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $booking->payment_status = $request->status;
        $booking->save();

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