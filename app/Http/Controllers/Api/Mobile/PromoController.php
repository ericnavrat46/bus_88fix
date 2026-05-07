<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoBanner;

class PromoController extends Controller
{
    // GET /api/promo/active
    public function getActivePromo()
    {
        $promos = PromoBanner::active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn($p) => $this->formatPromo($p));

        return response()->json([
            'success' => true,
            'message' => 'Promo aktif',
            'data'    => $promos,
        ]);
    }

    // POST /api/promo/detail
    public function getPromoDetail(Request $request)
    {
        $request->validate(['promo_id' => 'required|integer']);

        $promo = PromoBanner::find($request->promo_id);

        if (!$promo)
            return response()->json(['success' => false, 'message' => 'Promo tidak ditemukan.'], 404);

        return response()->json([
            'success' => true,
            'data'    => $this->formatPromo($promo),
        ]);
    }

    // POST /api/promo/apply
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

        if ($promo->is_quota_habis)
            return response()->json(['success' => false, 'message' => 'Kuota promo sudah habis.'], 422);

        // Cek minimum transaksi
        if ($promo->min_transaction > 0 && $request->original_price < $promo->min_transaction)
            return response()->json([
                'success' => false,
                'message' => 'Minimum transaksi Rp ' . number_format($promo->min_transaction, 0, ',', '.'),
            ], 422);

        $discount = $promo->calculateDiscount($request->original_price);

        return response()->json([
            'success'         => true,
            'promo_id'        => $promo->id,
            'title'           => $promo->title,
            'discount_type'   => $promo->discount_type,
            'discount_value'  => $promo->discount_value,
            'discount_amount' => $discount,
            'original_price'  => $request->original_price,
            'final_price'     => max(0, $request->original_price - $discount),
        ]);
    }

    // POST /api/promo/confirm
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

    // ── Helper ───────────────────────────────────────────────

    private function formatPromo(PromoBanner $promo): array
    {
        return [
            'id'              => $promo->id,
            'title'           => $promo->title,
            'description'     => $promo->description,
            'promo_code'      => $promo->promo_code,
            'image'           => $promo->image_url,
            'target_type'     => $promo->target_type,
            'discount_type'   => $promo->discount_type,
            'discount_value'  => $promo->discount_value,
            'min_transaction' => $promo->min_transaction,
            'max_discount'    => $promo->max_discount,
            'quota'           => $promo->quota,
            'used_quota'      => $promo->used_quota,
            'start_date'      => $promo->start_date?->format('Y-m-d'),
            'end_date'        => $promo->end_date?->format('Y-m-d'),
            'is_active'       => $promo->is_active,
            'is_expired'      => $promo->is_expired,
            'is_quota_habis'  => $promo->is_quota_habis,
            'sort_order'      => $promo->sort_order,
        ];
    }
}