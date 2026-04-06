<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    // ✅ Sudah ada — tidak diubah
    public function getActivePromo()
    {
        $now = now();

        $promos = DB::table('flash_sales')
            ->where('is_active', 1)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->whereColumn('used_quota', '<', 'quota')
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Promo aktif',
            'data'    => $promos,
        ]);
    }

    // 🆕 Ambil detail promo + data target (rute/jadwal/paket)
    public function getPromoDetail(Request $request)
    {
        $request->validate(['promo_id' => 'required|integer']);

        $promo = DB::table('flash_sales')->where('id', $request->promo_id)->first();

        if (!$promo)
            return response()->json(['success' => false, 'message' => 'Promo tidak ditemukan.'], 404);

        $target = null;
        switch ($promo->target_type) {
            case 'schedule':
                $target = DB::table('schedules')->where('id', $promo->target_id)->first();
                break;
            case 'tour_package':
                    $target = DB::table('tour_packages')->where('id', $promo->target_id)->first();
                    break;
            case 'rental':
                $target = DB::table('buses')->where('id', $promo->target_id)->first();
                break;
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'promo'  => $promo,
                'target' => $target,
            ],
        ]);
    }

    // 🆕 Hitung diskon — dipanggil saat masuk checkout
    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_id'       => 'required|integer',
            'original_price' => 'required|numeric|min:0',
            'user_id'        => 'required|integer',
        ]);

        $promo = DB::table('flash_sales')->where('id', $request->promo_id)->first();

        if (!$promo)
            return response()->json(['success' => false, 'message' => 'Promo tidak ditemukan.'], 404);

        if (!$promo->is_active || now()->lt($promo->start_time) || now()->gt($promo->end_time))
            return response()->json(['success' => false, 'message' => 'Promo tidak valid atau sudah kedaluwarsa.'], 422);

        if ($promo->used_quota >= $promo->quota)
            return response()->json(['success' => false, 'message' => 'Kuota promo sudah habis.'], 422);

        // Cek apakah user sudah pakai promo ini
        $alreadyUsed = DB::table('user_flash_sales')
            ->where('user_id', $request->user_id)
            ->where('flash_sale_id', $promo->id)
            ->exists();

        if ($alreadyUsed)
            return response()->json(['success' => false, 'message' => 'Kamu sudah pernah menggunakan promo ini.'], 422);

        // Hitung diskon
        $original = $request->original_price;
        $discount = $promo->discount_type === 'percent'
            ? $original * ($promo->discount_value / 100)
            : min($promo->discount_value, $original);

        return response()->json([
            'success'         => true,
            'promo_id'        => $promo->id,
            'title'           => $promo->title,
            'discount_amount' => $discount,
            'original_price'  => $original,
            'final_price'     => max(0, $original - $discount),
        ]);
    }

    // 🆕 Confirm promo — dipanggil SETELAH transaksi berhasil disimpan
    public function confirmPromo(Request $request)
    {
        $request->validate([
            'promo_id' => 'required|integer',
            'user_id'  => 'required|integer',
        ]);

        $promo = DB::table('flash_sales')->where('id', $request->promo_id)->first();

        if (!$promo)
            return response()->json(['success' => false, 'message' => 'Promo tidak ditemukan.'], 404);

        DB::transaction(function () use ($promo, $request) {
            DB::table('flash_sales')
                ->where('id', $promo->id)
                ->increment('used_quota');

            DB::table('user_flash_sales')->insertOrIgnore([
                'user_id'       => $request->user_id,
                'flash_sale_id' => $promo->id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Promo berhasil diterapkan.']);
    }
}