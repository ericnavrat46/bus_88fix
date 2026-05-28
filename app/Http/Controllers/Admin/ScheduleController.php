<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['bus', 'route']);

        // Search by Route (Origin/Destination) or Bus Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('route', function($qr) use ($search) {
                    $qr->where('origin', 'like', "%$search%")
                       ->orWhere('destination', 'like', "%$search%");
                })->orWhereHas('bus', function($qb) use ($search) {
                    $qb->where('name', 'like', "%$search%");
                });
            });
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('departure_date', $request->date);
        }

        $schedules = $query->latest('departure_date')
            ->latest('departure_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $buses = Bus::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        return view('admin.schedules.form', compact('buses', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_date' => 'required|date',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,cancelled',
        ]);

        $conflict = Schedule::where('bus_id', $validated['bus_id'])
            ->whereDate('departure_date', $validated['departure_date'])
            ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Bus ini sudah memiliki jadwal reguler pada tanggal tersebut. Silakan pilih bus lain atau ganti tanggal.');
        }

        // Cek bentrok dengan jadwal Sewa (Rental)
        $conflictRental = \App\Models\Rental::where('bus_id', $validated['bus_id'])
            ->where('approval_status', '!=', 'rejected')
            ->whereDate('start_date', '<=', $validated['departure_date'])
            ->whereDate('end_date', '>=', $validated['departure_date'])
            ->exists();

        if ($conflictRental) {
            return back()->withInput()->with('error', 'Bus ini sedang disewa (Rental) pada tanggal tersebut. Silakan pilih bus lain atau ganti tanggal.');
        }

        $bus = Bus::findOrFail($validated['bus_id']);
        $validated['available_seats'] = $bus->capacity;

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        $buses = Bus::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        return view('admin.schedules.form', compact('schedule', 'buses', 'routes'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_date' => 'required|date',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,cancelled,completed',
        ]);

        $conflict = Schedule::where('bus_id', $validated['bus_id'])
            ->whereDate('departure_date', $validated['departure_date'])
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Bus ini sudah memiliki jadwal reguler pada tanggal tersebut. Silakan pilih bus lain atau ganti tanggal.');
        }

        // Cek bentrok dengan jadwal Sewa (Rental)
        $conflictRental = \App\Models\Rental::where('bus_id', $validated['bus_id'])
            ->where('approval_status', '!=', 'rejected')
            ->whereDate('start_date', '<=', $validated['departure_date'])
            ->whereDate('end_date', '>=', $validated['departure_date'])
            ->exists();

        if ($conflictRental) {
            return back()->withInput()->with('error', 'Bus ini sedang disewa (Rental) pada tanggal tersebut. Silakan pilih bus lain atau ganti tanggal.');
        }

        // Jika bus diganti, sesuaikan available_seats dengan kapasitas bus baru
        if ((int)$validated['bus_id'] !== $schedule->bus_id) {
            $newBus = Bus::findOrFail($validated['bus_id']);
            $seatsBooked = $schedule->bus->capacity - $schedule->available_seats;
            $validated['available_seats'] = max(0, $newBus->capacity - $seatsBooked);
        }

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui!');

    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}
