<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::latest()->paginate(15);
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('admin.buses.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:buses',
            'type' => 'required|in:ekonomi,eksekutif',
            'capacity' => 'required|integer|min:10|max:60',
            'plate_number' => 'required|string|max:20|unique:buses',
            'facilities' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
        ]);

        Bus::create($validated);

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus berhasil ditambahkan!');
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.form', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:buses,code,' . $bus->id,
            'type' => 'required|in:ekonomi,eksekutif',
            'capacity' => 'required|integer|min:10|max:60',
            'plate_number' => 'required|string|max:20|unique:buses,plate_number,' . $bus->id,
            'facilities' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
        ]);

        $oldCapacity = $bus->capacity;

        $bus->update($validated);

        // Jika kapasitas berubah, update available_seats pada jadwal mendatang
        if ($oldCapacity !== (int) $validated['capacity']) {
            $newCapacity = (int) $validated['capacity'];
            $capacityDiff = $newCapacity - $oldCapacity;

            $bus->schedules()
                ->where('departure_date', '>=', Carbon::today())
                ->where('status', 'active')
                ->get()
                ->each(function (Schedule $schedule) use ($capacityDiff, $newCapacity) {
                    // Kursi terjual = kapasitas lama - tersisa saat ini
                    $newAvailable = max(0, $schedule->available_seats + $capacityDiff);
                    $schedule->update(['available_seats' => $newAvailable]);
                });
        }

        return redirect()->route('admin.buses.index')
            ->with('success', 'Data bus berhasil diperbarui! Jadwal terkait juga telah disesuaikan.');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus berhasil dihapus!');
    }
}
