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
}
