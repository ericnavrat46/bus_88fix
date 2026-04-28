<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Schedule;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $routes = \App\Models\Route::where('status', 'active')->get();
        $origins = $routes->pluck('origin')->unique()->sort()->values();
        $destinations = $routes->pluck('destination')->unique()->sort()->values();
        $reviews = \App\Models\Review::with('user', 'reviewable')->where('is_visible', true)->latest()->take(6)->get();
        $promoBanners = \App\Models\PromoBanner::active()->get();

        return view('home', compact('routes', 'origins', 'destinations', 'reviews', 'promoBanners'));
    }

    public function searchSchedules(Request $request)
    {
        $rules = [
            'origin' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'trip_type' => 'nullable|in:one_way,round_trip',
        ];

        if ($request->input('trip_type') === 'round_trip') {
            $rules['return_date'] = 'required|date|after_or_equal:date';
        }

        $validated = $request->validate($rules);

        $tripType = $validated['trip_type'] ?? 'one_way';

        // Outbound schedules
        $schedules = Schedule::with(['bus', 'route'])
            ->whereHas('route', function ($q) use ($validated) {
                $q->where('origin', $validated['origin'])
                  ->where('destination', $validated['destination']);
            })
            ->where('departure_date', $validated['date'])
            ->where('status', 'active')
            ->orderBy('departure_time')
            ->get();

        $viewData = [
            'schedules' => $schedules,
            'origin' => $validated['origin'],
            'destination' => $validated['destination'],
            'date' => $validated['date'],
            'tripType' => $tripType,
            'returnDate' => null,
            'returnSchedules' => collect(),
        ];

        // Return schedules (swapped origin <-> destination)
        if ($tripType === 'round_trip' && !empty($validated['return_date'])) {
            $returnSchedules = Schedule::with(['bus', 'route'])
                ->whereHas('route', function ($q) use ($validated) {
                    $q->where('origin', $validated['destination'])
                      ->where('destination', $validated['origin']);
                })
                ->where('departure_date', $validated['return_date'])
                ->where('status', 'active')
                ->orderBy('departure_time')
                ->get();

            $viewData['returnDate'] = $validated['return_date'];
            $viewData['returnSchedules'] = $returnSchedules;
        }

        return view('schedules.search-results', $viewData);
    }

    public function promoDetail(FlashSale $flashSale)
    {
        return view('promo.detail', compact('flashSale'));
    }

}
