<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{

    /// 🔥 CREATE RENTAL (WAJIB ADA)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'pickup_location' => 'required',
            'destination' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'purpose' => 'required',
            'passenger_count' => 'required|integer|min:1',
            'bus_id' => 'nullable'
        ]);

        $code = 'RNT-' . date('YmdHis');

        $start    = \Carbon\Carbon::parse($request->start_date);
        $end      = \Carbon\Carbon::parse($request->end_date);
        $duration = $start->diffInDays($end) + 1;

        $rental = Rental::create([
            'rental_code'     => $code,
            'user_id'         => $request->user_id,
            'bus_id'          => $request->bus_id,
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
            'duration_days'   => $duration,
            'pickup_location' => $request->pickup_location,
            'destination'     => $request->destination,
            'contact_name'    => $request->contact_name,
            'contact_phone'   => $request->contact_phone,
            'purpose'         => $request->purpose,
            'passenger_count' => $request->passenger_count,
            'approval_status' => 'pending',
            'payment_status'  => 'unpaid'
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Rental berhasil dibuat',
            'data'    => $rental
        ]);
    }


    public function myRentals($user_id)
    {
        $rentals = DB::table('rentals')
            ->join('users', 'rentals.user_id', '=', 'users.id')
            ->leftJoin('buses', 'rentals.bus_id', '=', 'buses.id')
            ->select(
                'rentals.*',
                'users.name',
                'users.email',
                'users.phone',
                'buses.name as bus_name'
            )
            ->where('rentals.user_id', $user_id)
            ->orderBy('rentals.created_at', 'desc')
            ->get();

        foreach ($rentals as $r) {

            $status = 'waiting_approval';

            if ($r->approval_status == 'rejected') {
                $status = 'rejected';
            } elseif ($r->approval_status == 'pending') {
                $status = 'waiting_approval';
            } elseif ($r->approval_status == 'approved') {
                if ($r->payment_status == 'cancelled') {
                    $status = 'cancelled';
                } elseif ($r->payment_status == 'paid') {
                    if (now()->gt(\Carbon\Carbon::parse($r->end_date)->addDay())) {
                        $status = 'completed';
                    } else {
                        $status = 'paid';
                    }
                } else {
                    $status = 'pending_payment';
                }
            }

            $r->status_final = $status;
        }

        return response()->json([
            'status' => true,
            'data'   => $rentals
        ]);
    }


    public function cancel(Request $request, $id)
    {
        $rental = Rental::find($id);

        if (!$rental) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        if ($rental->payment_status == 'paid') {
            return response()->json([
                'status'  => false,
                'message' => 'Sudah dibayar, hubungi admin'
            ]);
        }

        $rental->payment_status = 'cancelled';
        $rental->cancel_reason  = $request->reason;
        $rental->cancelled_at   = now();
        $rental->save();

        // 🔥 NOTIF KE USER — rental dibatalkan
        \App\Helpers\NotificationHelper::send(
            $rental->user_id,
            'Rental Dibatalkan ❌',
            'Rental dengan kode ' . $rental->rental_code . ' telah dibatalkan.',
            'rental_cancelled'
        );

        return response()->json([
            'status'  => true,
            'message' => 'Berhasil dibatalkan'
        ]);
    }


    public function finish($id)
    {
        DB::table('rentals')
            ->where('id', $id)
            ->update([
                'end_date'   => now()->subDay(),
                'updated_at' => now()
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Selesai'
        ]);
    }


    public function uploadPayment(Request $request)
    {
        $request->validate([
            'id'            => 'required',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $rental = Rental::find($request->id);

        if (!$rental) {
            return response()->json([
                'status'  => false,
                'message' => 'Rental tidak ditemukan'
            ], 404);
        }

        $file = $request->file('payment_proof');
        $path = $file->store('payment_proofs/rentals', 'public');

        $rental->payment_proof  = $path;
        $rental->payment_status = 'pending';
        $rental->save();

        // 🔥 NOTIF KE ADMIN — ada bukti bayar masuk
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            \App\Helpers\NotificationHelper::send(
                $admin->id,
                'Bukti Pembayaran Masuk 💳',
                'Ada bukti bayar baru dari rental ' . $rental->rental_code,
                'payment_proof'
            );
        }

        return response()->json([
            'status'  => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'path'    => $path
        ]);
    }


    // 🔥 CONFIRM PAYMENT — Admin konfirmasi / tolak pembayaran
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'id'     => 'required',
            'status' => 'required|in:paid,cancelled'
        ]);

        $rental = Rental::find($request->id);

        if (!$rental) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rental->payment_status = $request->status;
        $rental->save();

        // 🔥 NOTIF KE USER — kasih tahu hasil konfirmasi
        $title = $request->status == 'paid'
            ? 'Pembayaran Dikonfirmasi ✅'
            : 'Pembayaran Ditolak ❌';

        $body = $request->status == 'paid'
            ? 'Pembayaran rental ' . $rental->rental_code . ' telah dikonfirmasi. Selamat menikmati perjalanan!'
            : 'Maaf, pembayaran rental ' . $rental->rental_code . ' ditolak. Silakan hubungi admin.';

        \App\Helpers\NotificationHelper::send(
            $rental->user_id,
            $title,
            $body,
            'payment_status'
        );

        return response()->json([
            'status'  => true,
            'message' => 'Status pembayaran berhasil diupdate'
        ]);
    }
}