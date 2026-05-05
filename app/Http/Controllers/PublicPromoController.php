<?php

namespace App\Http\Controllers;

use App\Models\PromoBanner;
use Illuminate\Http\Request;

class PublicPromoController extends Controller
{
    public function index(Request $request)
    {
        $query = PromoBanner::active();

        // Handle Filter
        if ($request->has('filter') && $request->filter != '') {
            $filter = $request->filter;
            if ($filter == 'bus') {
                $query->where(function($q) {
                    $q->where('title', 'like', '%bus%')
                      ->orWhere('description', 'like', '%bus%')
                      ->orWhere('title', 'like', '%tiket%');
                });
            } elseif ($filter == 'wisata') {
                $query->where(function($q) {
                    $q->where('title', 'like', '%wisata%')
                      ->orWhere('description', 'like', '%wisata%')
                      ->orWhere('title', 'like', '%tour%');
                });
            }
        }

        // Handle Sort
        if ($request->has('sort')) {
            $sort = $request->sort;
            if ($sort == 'terbaru') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort == 'segera_berakhir') {
                $query->orderBy('end_date', 'asc');
            }
        } else {
            $query->orderBy('sort_order', 'asc'); // Default sort
        }

        $promos = $query->get();
        return view('promos.index', compact('promos'));
    }

    public function show(PromoBanner $promo)
    {
        if (!$promo->is_active || $promo->is_expired) {
            abort(404);
        }
        return view('promos.show', compact('promo'));
    }

    public function validatePromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
            'target_type' => 'required|string|in:ticket,rental,tour',
            'amount' => 'required|numeric|min:0'
        ]);

        $promo = PromoBanner::active()
            ->where('promo_code', strtoupper($request->promo_code))
            ->first();

        if (!$promo) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak ditemukan atau sudah tidak aktif.'
            ]);
        }

        if (!$promo->isValidFor($request->target_type)) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo ini tidak berlaku untuk layanan ini atau kuota telah habis.'
            ]);
        }

        if ($promo->min_transaction && $request->amount < $promo->min_transaction) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimal transaksi untuk promo ini adalah Rp ' . number_format($promo->min_transaction, 0, ',', '.')
            ]);
        }

        $discountAmount = $promo->calculateDiscount($request->amount);

        return response()->json([
            'valid' => true,
            'discount_amount' => $discountAmount,
            'message' => 'Promo berhasil digunakan!',
            'promo_id' => $promo->id
        ]);
    }
}
