<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
   public function index()
{
    $data = TourPackage::where('status', 'active')->get();

    $data->map(function ($item) {
        $item->image_url = asset('storage/' . $item->image);
        return $item;
    });

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}
}
