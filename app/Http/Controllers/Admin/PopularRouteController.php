<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopularRoute;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopularRouteController extends Controller
{
    public function index()
    {
        $popularRoutes = PopularRoute::with('route')
            ->orderBy('sort_order')
            ->paginate(10);
        return view('admin.popular-routes.index', compact('popularRoutes'));
    }

    public function create()
    {
        $routes = Route::where('status', 'active')->get();
        return view('admin.popular-routes.form', compact('routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price_display' => 'nullable|string|max:50',
            'duration_display' => 'nullable|string|max:50',
            'class_display' => 'nullable|string|in:Eksekutif,Ekonomi',
            'badge_text' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('popular_routes', $filename, 'public');
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');

        PopularRoute::create($validated);

        return redirect()->route('admin.popular-routes.index')
            ->with('success', 'Rute populer berhasil ditambahkan.');
    }

    public function edit(PopularRoute $popularRoute)
    {
        $routes = Route::where('status', 'active')->get();
        return view('admin.popular-routes.form', compact('popularRoute', 'routes'));
    }

    public function update(Request $request, PopularRoute $popularRoute)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price_display' => 'nullable|string|max:50',
            'duration_display' => 'nullable|string|max:50',
            'class_display' => 'nullable|string|in:Eksekutif,Ekonomi',
            'badge_text' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($popularRoute->image) {
                Storage::disk('public')->delete('popular_routes/' . $popularRoute->image);
            }
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('popular_routes', $filename, 'public');
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');

        $popularRoute->update($validated);

        return redirect()->route('admin.popular-routes.index')
            ->with('success', 'Rute populer berhasil diperbarui.');
    }

    public function destroy(PopularRoute $popularRoute)
    {
        if ($popularRoute->image) {
            Storage::disk('public')->delete('popular_routes/' . $popularRoute->image);
        }
        $popularRoute->delete();

        return redirect()->route('admin.popular-routes.index')
            ->with('success', 'Rute populer berhasil dihapus.');
    }
}
