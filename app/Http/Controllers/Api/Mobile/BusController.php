<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    public function index()
    {
        $buses = DB::table('buses')
            ->where('status', 'active') 
            ->select(
                'id',
                'name',
                'code',
                'type',
                'capacity',
                'plate_number',
                'image',
                'facilities'
            )
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $buses
        ]);
    }
    public function show($id)
    {
        $bus = DB::table('buses')->where('id', $id)->first();

        if (!$bus) {
            return response()->json([
                'status' => false,
                'message' => 'Bus tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $bus
        ]);
    }
}