<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index()
    {
        $data = TourPackage::where('status', 'active')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = TourPackage::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}