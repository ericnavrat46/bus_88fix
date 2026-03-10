<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $routes = Route::where('status', 'active')->get();
        $origins = $routes->pluck('origin')->unique()->sort()->values();
        $destinations = $routes->pluck('destination')->unique()->sort()->values();

        return view('home', compact('routes', 'origins', 'destinations'));
    }

    public function searchSchedules(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $schedules = Schedule::with(['bus', 'route'])
            ->whereHas('route', function ($q) use ($validated) {
                $q->where('origin', $validated['origin'])
                  ->where('destination', $validated['destination']);
            })
            ->where('departure_date', $validated['date'])
            ->where('status', 'active')
            ->orderBy('departure_time')
            ->get();

        return view('schedules.search-results', [
            'schedules' => $schedules,
            'origin' => $validated['origin'],
            'destination' => $validated['destination'],
            'date' => $validated['date'],
        ]);
    }
}
