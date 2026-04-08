<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Mail\SendOTPMail;

class ProfileWebController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'nullable|string|max:255',
            'email'   => 'nullable|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password'=> 'nullable|min:6',
            'avatar'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->filled('name')) $user->name = $request->name;
        if ($request->filled('email')) $user->email = $request->email;
        if ($request->filled('address')) $user->address = $request->address;
        if ($request->filled('phone')) $user->phone = $request->phone;
        if ($request->filled('password')) $user->password = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path('avatar/' . $user->avatar))) {
                unlink(public_path('avatar/' . $user->avatar));
            }
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('avatar'), $filename);
            $user->avatar = $filename;
        }

        $user->save();
        return redirect()->route('dashboard')->with('success', 'Profile berhasil diupdate');
    }

    // ✅ STEP 1: Kirim OTP dengan COOLDOWN 60 DETIK (DIPERBAIKI)
    public function sendOtpPassword(Request $request)
    {
        $user = auth()->user();

        // 🔥 PERBAIKAN: Hitung sisa cooldown dengan benar
        $lastSent = $user->last_otp_sent_at ? Carbon::parse($user->last_otp_sent_at) : null;

if ($lastSent) {
    $secondsPassed = now()->timestamp - $lastSent->timestamp; // raw PHP, pasti akurat
    if ($secondsPassed < 60) {
        $remaining = 60 - $secondsPassed;
        return response()->json([
            'success'   => false,
            'message'   => "Harap tunggu {$remaining} detik sebelum mengirim ulang OTP",
            'remaining' => (int) $remaining
        ], 429);
    }
}

        // Generate OTP 6 digit
        $otp = rand(100000, 999999);

        // Simpan ke database dengan waktu sekarang
        $user->otp = (string) $otp;
        $user->expired_otp = now()->addMinutes(5);
        $user->last_otp_sent_at = now();
        $user->save();

        // Hapus session verifikasi sebelumnya
        Session::forget(['otp_verified_for_password_change', 'otp_verified_at']);

        // Kirim email
        try {
            Mail::to($user->email)->send(new SendOTPMail($otp));
            Log::info("OTP sent to {$user->email}: {$otp}");
            
            return response()->json([
                'success' => true,
                'message' => 'OTP berhasil dikirim ke email ' . $user->email,
                'expires_in' => 300
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send OTP: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP. Silakan coba lagi.'
            ], 500);
        }
    }

    // ✅ STEP 2: Verifikasi OTP
    public function verifyOtpOnly(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        if (!$user->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan kirim OTP terlebih dahulu.'
            ], 400);
        }

        if (now()->gt($user->expired_otp)) {
            $user->otp = null;
            $user->expired_otp = null;
            $user->save();
            
            return response()->json([
                'success' => false,
                'message' => 'OTP sudah kadaluarsa (5 menit). Silakan kirim ulang.'
            ], 400);
        }

        if ($user->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => '❌ Kode OTP yang Anda masukkan SALAH.'
            ], 400);
        }

        Session::put('otp_verified_for_password_change', true);
        Session::put('otp_verified_at', now()->toDateTimeString());

        $user->otp = null;
        $user->expired_otp = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => '✅ OTP berhasil diverifikasi! Silakan masukkan password baru Anda.'
        ]);
    }

    // ✅ STEP 3: Ganti Password
    public function changePassword(Request $request)
    {
        if (!Session::get('otp_verified_for_password_change')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan verifikasi OTP terlebih dahulu.'
            ], 403);
        }

        $verifiedAt = Session::get('otp_verified_at');
        if ($verifiedAt && now()->diffInMinutes(Carbon::parse($verifiedAt)) > 10) {
            Session::forget(['otp_verified_for_password_change', 'otp_verified_at']);
            return response()->json([
                'success' => false,
                'message' => 'Sesi verifikasi telah kadaluarsa (10 menit).'
            ], 403);
        }

        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        Session::forget(['otp_verified_for_password_change', 'otp_verified_at']);

        return response()->json([
            'success' => true,
            'message' => '✅ Password berhasil diubah!'
        ]);
    }

    // ✅ Cek status verifikasi
    public function checkVerificationStatus()
    {
        $isVerified = Session::get('otp_verified_for_password_change', false);
        $verifiedAt = Session::get('otp_verified_at');
        $expiresIn = 0;
        
        if ($isVerified && $verifiedAt) {
            $verifiedAtCarbon = Carbon::parse($verifiedAt);
            $expiresIn = 600 - now()->diffInSeconds($verifiedAtCarbon);
            
            if ($expiresIn <= 0) {
                Session::forget(['otp_verified_for_password_change', 'otp_verified_at']);
                $isVerified = false;
                $expiresIn = 0;
            }
        }

        return response()->json([
            'verified' => $isVerified,
            'expires_in' => (int) max(0, $expiresIn)
        ]);
    }
    
}