<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoBannerController extends Controller
{
    public function index(Request $request)
    {
        $query = PromoBanner::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', true)->where('end_date', '>=', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('promo_code', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'sort_order');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $banners = $query->paginate(10);

        return view('admin.promo-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.promo-banners.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,png,webp|max:2048',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:200',
            'promo_code' => 'required|string|alpha_num|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'link' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/promo_banners', $filename);
            $validated['image'] = $filename;
        }

        $validated['promo_code'] = strtoupper($validated['promo_code']);
        $validated['is_active'] = $request->has('is_active');

        PromoBanner::create($validated);

        return redirect()->route('admin.promo-banners.index')->with('success', 'Banner promo berhasil ditambahkan.');
    }

    public function edit(PromoBanner $promoBanner)
    {
        return view('admin.promo-banners.form', ['banner' => $promoBanner]);
    }

    public function update(Request $request, PromoBanner $promoBanner)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:200',
            'promo_code' => 'required|string|alpha_num|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'link' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($promoBanner->image) {
                Storage::delete('public/promo_banners/' . $promoBanner->image);
            }
            
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/promo_banners', $filename);
            $validated['image'] = $filename;
        }

        $validated['promo_code'] = strtoupper($validated['promo_code']);
        $validated['is_active'] = $request->has('is_active');

        $promoBanner->update($validated);

        return redirect()->route('admin.promo-banners.index')->with('success', 'Banner promo berhasil diperbarui.');
    }

    public function destroy(PromoBanner $promoBanner)
    {
        if ($promoBanner->image) {
            Storage::delete('public/promo_banners/' . $promoBanner->image);
        }
        $promoBanner->delete();

        return redirect()->route('admin.promo-banners.index')->with('success', 'Banner promo berhasil dihapus.');
    }

    public function toggleStatus(PromoBanner $promoBanner)
    {
        $promoBanner->update(['is_active' => !$promoBanner->is_active]);
        return response()->json(['success' => true, 'is_active' => $promoBanner->is_active]);
    }
}
