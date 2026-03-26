<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TourBooking; // 🔥 WAJIB
use Illuminate\Support\Str; // 🔥 WAJIB

class TourBookingController extends Controller
{
    public function store(Request $request)
    {
        $booking = TourBooking::create([
            'booking_code' => 'TRP-' . strtoupper(Str::random(6)),
            'user_id' => $request->user_id,
            'tour_package_id' => $request->tour_package_id,
            'travel_date' => $request->travel_date,
            'passenger_count' => $request->passenger_count,
            'total_price' => $request->total_price,
            'payment_status' => 'pending',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }
}
