<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    public function index()
    {
        $buses = DB::table('buses')
            ->where('status', 'active') 
            ->select(
                'id',
                'name',
                'code',
                'type',
                'capacity',
                'plate_number',
                'image',
                'facilities'
            )
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $buses
        ]);
    }
    public function show($id)
    {
        $bus = DB::table('buses')->where('id', $id)->first();

        if (!$bus) {
            return response()->json([
                'status' => false,
                'message' => 'Bus tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $bus
        ]);
    }
    public function available(Request $request)
{
    $date = $request->query('date'); // format: Y-m-d
    $duration = (int) $request->query('duration', 1);

    if (!$date) {
        return response()->json(['status' => false, 'message' => 'date required'], 422);
    }

    $startDate = \Carbon\Carbon::parse($date);
    $endDate = $startDate->copy()->addDays($duration - 1);

    // Ambil semua bus aktif
    $allBuses = DB::table('buses')->where('status', 'active')
        ->select('id', 'name', 'capacity', 'plate_number')
        ->orderBy('name')->get();

    // Cari bus yang BENTROK
    $busyBySchedule = \App\Models\Schedule::where('status', '!=', 'cancelled')
        ->whereDate('departure_date', '>=', $startDate)
        ->whereDate('departure_date', '<=', $endDate)
        ->pluck('bus_id')->toArray();

    $busyByRental = \App\Models\Rental::where('approval_status', '!=', 'rejected')
        ->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        })->pluck('bus_id')->toArray();

   $busyByTour = \App\Models\TourBooking::whereIn('payment_status', ['paid', 'pending'])
    ->join('tour_packages', 'tour_bookings.tour_package_id', '=', 'tour_packages.id')
    ->where(function ($q) use ($startDate, $endDate) {
        $q->whereRaw(
            "travel_date <= ? AND DATE_ADD(travel_date, INTERVAL tour_packages.duration_days - 1 DAY) >= ?",
            [$endDate->toDateString(), $startDate->toDateString()]
        );
    })
    ->pluck('tour_bookings.bus_id')->toArray();

    $busyIds = array_unique(array_merge($busyBySchedule, $busyByRental, $busyByTour));

    // Tandai tiap bus: available atau tidak
    $result = $allBuses->map(function ($bus) use ($busyIds) {
        return [
            'id' => $bus->id,
            'name' => $bus->name,
            'capacity' => $bus->capacity,
            'plate_number' => $bus->plate_number,
            'available' => !in_array($bus->id, $busyIds),
        ];
    });

    return response()->json(['status' => true, 'data' => $result]);
}
}