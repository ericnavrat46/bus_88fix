<?php

namespace App\Http\Controllers;

use App\Models\PromoBanner;
use Illuminate\Http\Request;

class PublicPromoController extends Controller
{
    public function index()
    {
        $promos = PromoBanner::active()->get();
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
