<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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


    // GOOGLE LOGIN
    public function googleLogin(Request $request)
    {
        $request->validate([
            "google_id"=>"required",
            "email"=>"required|email",
            "name"=>"required"
        ]);

        $user = User::where('google_id',$request->google_id)->first();

        if(!$user){

            $user = User::where('email',$request->email)->first();

            if($user){
                $user->update([
                    "google_id"=>$request->google_id,
                    "avatar"=>$request->photo ?? null
                ]);
            }else{
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

        $requirePhone = $user->phone ? false : true;

        return response()->json([
            "status"=>true,
            "message"=>"Login Google berhasil",
            "data"=>$user,
            "require_phone"=>$requirePhone
        ]);
    }


    // =========================================
    // 🔥 FORGOT PASSWORD (KIRIM OTP)
    // =========================================
    public function forgotPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email"
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Email tidak ditemukan"
            ]);
        }

        // 🔥 CEGAH SPAM OTP
        if ($user->expired_otp && now()->lessThan($user->expired_otp)) {
            return response()->json([
                "status" => false,
                "message" => "OTP masih aktif, tunggu beberapa saat"
            ]);
        }

        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->expired_otp = now()->addMinutes(5);
        $user->save();

        Mail::raw("Kode OTP reset password kamu adalah: $otp (berlaku 5 menit)", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject("OTP Reset Password");
        });

        return response()->json([
            "status" => true,
            "message" => "OTP berhasil dikirim ke email"
        ]);
    }


    // =========================================
    // 🔥 VERIFY OTP
    // =========================================
    public function verifyOtpReset(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "otp" => "required"
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "OTP salah"
            ]);
        }

        if (now()->greaterThan($user->expired_otp)) {
            return response()->json([
                "status" => false,
                "message" => "OTP sudah kadaluarsa"
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "OTP valid"
        ]);
    }


    // =========================================
    // 🔥 RESET PASSWORD (WAJIB PAKAI OTP)
    // =========================================
    public function resetPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "otp" => "required",
            "password" => "required|min:6"
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "OTP tidak valid"
            ]);
        }

        if (now()->greaterThan($user->expired_otp)) {
            return response()->json([
                "status" => false,
                "message" => "OTP sudah kadaluarsa"
            ]);
        }

        $user->password = Hash::make($request->password);

        // 🔥 HAPUS OTP SETELAH DIPAKAI
        $user->otp = null;
        $user->expired_otp = null;

        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Password berhasil diubah"
        ]);
    }

}