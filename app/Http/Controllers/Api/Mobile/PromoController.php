<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoBanner;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function getActivePromo()
    {
        $promos = PromoBanner::active()->get();

        return response()->json([
            'success' => true,
            'message' => 'Promo aktif',
            'data'    => $promos,
        ]);
    }

    public function getPromoDetail(Request $request)
    {
        $request->validate(['promo_id' => 'required|integer']);

        $promo = PromoBanner::find($request->promo_id);

        if (!$promo)
            return response()->json(['success' => false, 'message' => 'Promo tidak ditemukan.'], 404);

        return response()->json([
            'success' => true,
            'data'    => [
                'promo'  => $promo,
                'target' => null, // Legacy field
            ],
        ]);
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_id'       => 'required|integer',
            'original_price' => 'required|numeric|min:0',
            'user_id'        => 'required|integer',
        ]);

        $promo = PromoBanner::find($request->promo_id);

        if (!$promo)
            return response()->json(['success' => false, 'message' => 'Promo tidak ditemukan.'], 404);

        if (!$promo->is_active || $promo->is_expired)
            return response()->json(['success' => false, 'message' => 'Promo tidak valid atau sudah kedaluwarsa.'], 422);

        if ($promo->quota > 0 && $promo->used_quota >= $promo->quota)
            return response()->json(['success' => false, 'message' => 'Kuota promo sudah habis.'], 422);

        $discount = $promo->calculateDiscount($request->original_price);

        return response()->json([
            'success'         => true,
            'promo_id'        => $promo->id,
            'title'           => $promo->title,
            'discount_amount' => $discount,
            'original_price'  => $request->original_price,
            'final_price'     => max(0, $request->original_price - $discount),
        ]);
    }

    public function confirmPromo(Request $request)
    {
        $request->validate([
            'promo_id' => 'required|integer',
            'user_id'  => 'required|integer',
        ]);

        $promo = PromoBanner::find($request->promo_id);

        if (!$promo)
            return response()->json(['success' => false, 'message' => 'Promo tidak ditemukan.'], 404);

        $promo->increment('used_quota');

        return response()->json(['success' => true, 'message' => 'Promo berhasil diterapkan.']);
    }
}