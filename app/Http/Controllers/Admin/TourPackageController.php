<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourPackageController extends Controller
{
    public function index()
    {
        $packages = TourPackage::latest()->paginate(10);
        return view('admin.tours.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.tours.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_days' => 'required|integer|min:1',
            'price_per_person' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'destinations' => 'required|string', // comma separated strings converted to array
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['destinations'] = array_map('trim', explode(',', $validated['destinations']));
        if ($request->has('inclusions')) {
            $validated['inclusions'] = array_filter(array_map('trim', explode("\n", $validated['inclusions'])));
        }
        if ($request->has('exclusions')) {
            $validated['exclusions'] = array_filter(array_map('trim', explode("\n", $validated['exclusions'])));
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('tour_packages', 'public');
        }

        TourPackage::create($validated);

        return redirect()->route('admin.tour-packages.index')->with('success', 'Paket wisata berhasil dibuat.');
    }

    public function edit(TourPackage $tour_package)
    {
        return view('admin.tours.edit', ['package' => $tour_package]);
    }

    public function update(Request $request, TourPackage $tour_package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_days' => 'required|integer|min:1',
            'price_per_person' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'destinations' => 'required|string',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['destinations'] = array_map('trim', explode(',', $validated['destinations']));
        $validated['inclusions'] = array_filter(array_map('trim', explode("\n", $request->inclusions)));
        $validated['exclusions'] = array_filter(array_map('trim', explode("\n", $request->exclusions)));

        if ($request->hasFile('image')) {
            if ($tour_package->image) {
                Storage::disk('public')->delete($tour_package->image);
            }
            $validated['image'] = $request->file('image')->store('tour_packages', 'public');
        }

        $tour_package->update($validated);

        return redirect()->route('admin.tour-packages.index')->with('success', 'Paket wisata berhasil diperbarui.');
    }

    public function destroy(TourPackage $tour_package)
    {
        if ($tour_package->image) {
            Storage::disk('public')->delete($tour_package->image);
        }
        $tour_package->delete();

        return redirect()->route('admin.tour-packages.index')->with('success', 'Paket wisata berhasil dihapus.');
    }
}
