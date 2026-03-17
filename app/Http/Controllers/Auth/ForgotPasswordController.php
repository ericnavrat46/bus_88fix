<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Mail\SendOTPMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->expired_otp = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new SendOTPMail($otp));

        session(['reset_email' => $request->email]);

        return redirect()->route('password.otp')->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);
        $email = session('reset_email');

        $user = User::where('email', $email)
            ->where('otp', $request->otp)
            ->where('expired_otp', '>', Carbon::now())
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        session(['otp_verified' => true]);

        return redirect()->route('password.reset');
    }

    public function showResetForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('password.otp');
        }
        return view('auth.passwords.reset');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Terjadi kesalahan, silakan coba lagi.']);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->expired_otp = null;
        $user->save();

        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan masuk.');
    }
}
