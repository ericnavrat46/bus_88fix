<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{

    public function profile($id)
    {

        $user = User::find($id);

        if(!$user){
            return response()->json([
                "status" => false,
                "message" => "User tidak ditemukan"
            ]);
        }

        return response()->json([
            "status" => true,
            "data" => $user
        ]);

    }

}
