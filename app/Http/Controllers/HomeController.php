<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Schedule;
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
        $popularRoutes = \App\Models\PopularRoute::with('route')->where('is_active', true)->orderBy('sort_order')->get();

        return view('home', compact('routes', 'origins', 'destinations', 'reviews', 'promoBanners', 'popularRoutes'));
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

        $endDate = \Carbon\Carbon::parse($validated['date'])->addDays(7)->toDateString();

        $query = Schedule::with(['bus', 'route'])
            ->whereHas('route', function ($q) use ($validated) {
                $q->where('origin', $validated['origin'])
                  ->where('destination', $validated['destination']);
            })
            ->whereBetween('departure_date', [$validated['date'], $endDate])
            ->where('status', 'active');

        // Filter out past schedules if the departure date is today
        if ($validated['date'] === now()->toDateString()) {
            $query->whereTime('departure_time', '>', now()->toTimeString());
        }

        // Apply Filters
        if ($request->filled('waktu')) {
            $waktu = $request->waktu;
            $query->where(function ($q) use ($waktu) {
                if (in_array('pagi', $waktu)) $q->orWhereTime('departure_time', '>=', '06:00:00')->whereTime('departure_time', '<=', '11:59:59');
                if (in_array('siang', $waktu)) $q->orWhereTime('departure_time', '>=', '12:00:00')->whereTime('departure_time', '<=', '17:59:59');
                if (in_array('malam', $waktu)) $q->orWhereTime('departure_time', '>=', '18:00:00')->whereTime('departure_time', '<=', '23:59:59');
            });
        }
        if ($request->filled('bus_id')) {
            $query->whereIn('bus_id', $request->bus_id);
        }
        if ($request->filled('harga')) {
            $range = explode('-', $request->harga);
            if (count($range) == 2) {
                $query->whereBetween('price', [(int)$range[0], (int)$range[1]]);
            }
        }

        // Outbound schedules
        $schedules = $query->orderBy('departure_date')->orderBy('departure_time')->get();

        $routes = \App\Models\Route::where('status', 'active')->get();
        $origins = $routes->pluck('origin')->unique()->sort()->values();
        $destinations = $routes->pluck('destination')->unique()->sort()->values();
        $availableBuses = \App\Models\Bus::where('status', 'active')->orderBy('name')->get();

        $viewData = [
            'schedules' => $schedules,
            'origin' => $validated['origin'],
            'destination' => $validated['destination'],
            'date' => $validated['date'],
            'tripType' => $tripType,
            'returnDate' => null,
            'returnSchedules' => collect(),
            'origins' => $origins,
            'destinations' => $destinations,
            'availableBuses' => $availableBuses,
        ];

        // Return schedules (swapped origin <-> destination)
        if ($tripType === 'round_trip' && !empty($validated['return_date'])) {
            $returnEndDate = \Carbon\Carbon::parse($validated['return_date'])->addDays(7)->toDateString();
            $returnQuery = Schedule::with(['bus', 'route'])
                ->whereHas('route', function ($q) use ($validated) {
                    $q->where('origin', $validated['destination'])
                      ->where('destination', $validated['origin']);
                })
                ->whereBetween('departure_date', [$validated['return_date'], $returnEndDate])
                ->where('status', 'active');

            // Filter out past return schedules if the return date is today
            if ($validated['return_date'] === now()->toDateString()) {
                $returnQuery->whereTime('departure_time', '>', now()->toTimeString());
            }

            // Apply Filters to return schedules
            if ($request->filled('waktu')) {
                $waktu = $request->waktu;
                $returnQuery->where(function ($q) use ($waktu) {
                    if (in_array('pagi', $waktu)) $q->orWhereTime('departure_time', '>=', '06:00:00')->whereTime('departure_time', '<=', '11:59:59');
                    if (in_array('siang', $waktu)) $q->orWhereTime('departure_time', '>=', '12:00:00')->whereTime('departure_time', '<=', '17:59:59');
                    if (in_array('malam', $waktu)) $q->orWhereTime('departure_time', '>=', '18:00:00')->whereTime('departure_time', '<=', '23:59:59');
                });
            }
            if ($request->filled('bus_id')) {
                $returnQuery->whereIn('bus_id', $request->bus_id);
            }
            if ($request->filled('harga')) {
                $range = explode('-', $request->harga);
                if (count($range) == 2) {
                    $returnQuery->whereBetween('price', [(int)$range[0], (int)$range[1]]);
                }
            }

            $returnSchedules = $returnQuery->orderBy('departure_date')->orderBy('departure_time')->get();

            $viewData['returnDate'] = $validated['return_date'];
            $viewData['returnSchedules'] = $returnSchedules;
        }

        return view('schedules.search-results', $viewData);
    }

}
