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



    // GOOGLE LOGIN (TAMBAHAN)
    public function googleLogin(Request $request)
    {
        $request->validate([
            "google_id"=>"required",
            "email"=>"required|email",
            "name"=>"required"
        ]);

        // cek user berdasarkan google_id
        $user = User::where('google_id',$request->google_id)->first();

        if(!$user){

            // cek jika email sudah ada
            $user = User::where('email',$request->email)->first();

            if($user){

                // update google_id jika user sudah ada
                $user->update([
                    "google_id"=>$request->google_id,
                    "avatar"=>$request->photo ?? null
                ]);

            }else{

                // buat user baru jika belum ada
                $user = User::create([
                    "name"=>$request->name,
                    "email"=>$request->email,
                    "google_id"=>$request->google_id,
                    "avatar"=>$request->photo ?? null,
                    "password"=>Hash::make("google_login"),
                    "role"=>"customer"
                ]);
            }
        }

        // 🔥 TAMBAHAN (INI INTINYA)
        $requirePhone = $user->phone ? false : true;

        return response()->json([
            "status"=>true,
            "message"=>"Login Google berhasil",
            "data"=>$user,
            "require_phone"=>$requirePhone
        ]);
    }

}