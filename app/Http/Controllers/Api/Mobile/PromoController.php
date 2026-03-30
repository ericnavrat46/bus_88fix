<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
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
            'data' => $promos
        ]);
    }
}