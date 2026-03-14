<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user){
            return response()->json([
                "status"=>false,
                "message"=>"Email tidak ditemukan"
            ]);
        }

        if(!Hash::check($request->password,$user->password)){
            return response()->json([
                "status"=>false,
                "message"=>"Password salah"
            ]);
        }

        return response()->json([
            "status"=>true,
            "message"=>"Login berhasil",
            "data"=>$user
        ]);
    }



    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            "name"=>"required",
            "email"=>"required|email|unique:users",
            "phone"=>"required",
            "password"=>"required|min:6"
        ]);

        $user = User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "phone"=>$request->phone,
            "password"=>Hash::make($request->password),
            "role"=>"customer"
        ]);

        return response()->json([
            "status"=>true,
            "message"=>"Register berhasil",
            "data"=>$user
        ]);
    }

}