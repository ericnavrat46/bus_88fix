<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TourBooking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TourBookingController extends Controller
{

    public function myBookings($user_id)
    {
        $data = DB::table('tour_bookings')
            ->join('users','tour_bookings.user_id','=','users.id')
            ->join('tour_packages','tour_bookings.tour_package_id','=','tour_packages.id')
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
            ->orderBy('tour_bookings.created_at','desc')
            ->get();


        foreach ($data as $t) {

            $status = 'pending_payment';

            if ($t->payment_status == 'cancelled') {
                $status = 'cancelled';
            }

            elseif ($t->payment_status == 'expired') {
                $status = 'expired';
            }

            elseif ($t->payment_status == 'paid') {

                if (now()->gt(\Carbon\Carbon::parse($t->travel_date)->addDay())) {
                    $status = 'completed';
                } else {
                    $status = 'paid';
                }
            }

           elseif ($t->payment_status == 'pending') {
                if ($t->payment_proof) {
                    $status = 'waiting_confirmation';
                } else {
                    $status = 'pending_payment';
                }
            }

            $t->status_final = $status;
        }

        return response()->json([
            'success' => true,
            'data' => $data
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
}
