<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Rental;
use App\Models\TourBooking;
use App\Models\TourPackage; // ← tambah

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'required|integer',
            'reviewable_type' => 'required|string',
            'reviewable_id'   => 'required|integer',
            'rating'          => 'required|integer|min:1|max:5',
            'comment'         => 'nullable|string|max:1000',
            'image'           => 'nullable|image|max:2048',
        ]);

        $typeMap = [
            'booking' => Booking::class,
            'rental'  => Rental::class,
            'tour'    => TourPackage::class, // ← ganti
        ];

        $modelClass = $typeMap[$validated['reviewable_type']] ?? null;

        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Tipe review tidak valid'], 400);
        }

        $reviewable = $modelClass::find($validated['reviewable_id']);

        if (!$reviewable) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // ── cek ownership & status per type ──
        if ($modelClass === TourPackage::class) {
            // cek user punya booking paid untuk paket ini
            $hasBooking = TourBooking::where('tour_package_id', $reviewable->id)
                ->where('user_id', $request->user_id)
                ->where('payment_status', 'paid')
                ->exists();

            if (!$hasBooking) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        } else {
            if ($reviewable->user_id != $request->user_id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            if ($reviewable->status_final != 'completed') {
                return response()->json(['success' => false, 'message' => 'Pesanan belum selesai'], 400);
            }
        }

        $alreadyReviewed = Review::where([
            'user_id'         => $request->user_id,
            'reviewable_type' => $modelClass,
            'reviewable_id'   => $reviewable->id,
        ])->exists();

        if ($alreadyReviewed) {
            return response()->json(['success' => false, 'message' => 'Anda sudah memberi review'], 400);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        $review = Review::create([
            'user_id'         => $request->user_id,
            'reviewable_type' => $modelClass,
            'reviewable_id'   => $reviewable->id,
            'rating'          => $validated['rating'],
            'comment'         => $validated['comment'],
            'image'           => $imagePath,
        ]);

        return response()->json(['success' => true, 'message' => 'Review berhasil dikirim', 'data' => $review]);
    }

    public function checkReview(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|integer',
            'reviewable_type' => 'required|string',
            'reviewable_id'   => 'required|integer',
        ]);

        $typeMap = [
            'booking' => Booking::class,
            'rental'  => Rental::class,
            'tour'    => TourPackage::class, // ← ganti
        ];

        $modelClass = $typeMap[$request->reviewable_type] ?? null;

        if (!$modelClass) {
            return response()->json(['reviewed' => false]);
        }

        $exists = Review::where([
            'user_id'         => $request->user_id,
            'reviewable_type' => $modelClass,
            'reviewable_id'   => $request->reviewable_id,
        ])->exists();

        return response()->json(['reviewed' => $exists]);
    }
}