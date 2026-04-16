<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|string',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        // Map short type to full class name
        $typeMap = [
            'booking' => \App\Models\Booking::class,
            'rental' => \App\Models\Rental::class,
            'tour' => \App\Models\TourBooking::class,
        ];

        $modelClass = $typeMap[$validated['reviewable_type']] ?? null;
        if (!$modelClass) {
            return back()->with('error', 'Tipe review tidak valid.');
        }

        $reviewable = $modelClass::findOrFail($validated['reviewable_id']);

        // Security: Ensure user owns this and it's paid
        if ($reviewable->user_id !== auth()->id() || $reviewable->payment_status !== 'paid') {
            abort(403, 'Unauthorized action.');
        }

        // Prevent duplicate reviews
        if ($reviewable->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        \App\Models\Review::create([
            'user_id' => auth()->id(),
            'reviewable_type' => $modelClass,
            'reviewable_id' => $reviewable->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
