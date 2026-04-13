<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index()
    {
        $data = TourPackage::where('status', 'active')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}