<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::latest()->paginate(15);
        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.routes.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'nullable|integer|min:1',
            'duration' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Route::create($validated);

        return redirect()->route('admin.routes.index')
            ->with('success', 'Rute berhasil ditambahkan!');
    }

    public function edit(Route $route)
    {
        return view('admin.routes.form', compact('route'));
    }

    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'nullable|integer|min:1',
            'duration' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $route->update($validated);

        return redirect()->route('admin.routes.index')
            ->with('success', 'Rute berhasil diperbarui!');
    }

    public function destroy(Route $route)
    {
        $route->delete();

        return redirect()->route('admin.routes.index')
            ->with('success', 'Rute berhasil dihapus!');
    }
}
