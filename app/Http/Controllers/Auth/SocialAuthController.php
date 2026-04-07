<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect ke Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google
     */
    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // 🔥 Cari atau buat user (ANTI GAGAL)
        $user = User::updateOrCreate(
            [
                'email' => $googleUser->getEmail(),
            ],
            [
                'name'      => $googleUser->getName() ?? 'User Google',
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'role'      => 'customer',
                'password'  => bcrypt('12345678'),
            ]
        );

        // 🔐 Login
        Auth::login($user, true);

        return redirect('/dashboard');

    } catch (\Exception $e) {
        dd('ERROR FINAL', $e->getMessage());
    }



        // 🔥 Validasi email (penting)
        if (!$googleUser->getEmail()) {
            return redirect()->route('login')
                ->with('error', 'Email dari Google tidak tersedia.');
        }

        try {
            // 🔍 Cari user
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // 🔄 Update jika belum ada google_id
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                    ]);
                }
            } else {
                // 🆕 Buat user baru
                $user = User::create([
                    'name'      => $googleUser->getName() ?? 'User Google',
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'role'      => 'customer',
                    'password'  => bcrypt(Str::random(16)), // 🔥 FIX WAJIB
                ]);
            }

            // 🔐 Login user
            Auth::login($user, true);

            // 🚀 Redirect
            return redirect('/dashboard');

        } catch (\Exception $e) {
            Log::error('DB/Login Error: ' . $e->getMessage());

            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat login.');
        }
    }
}