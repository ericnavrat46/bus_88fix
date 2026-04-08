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
    // Redirect ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback Google
    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        if (!$googleUser->getEmail()) {
            return redirect()->route('login')
                ->with('error', 'Email Google tidak tersedia');
        }

        $existingUser = User::where('email', $googleUser->getEmail())->first();

        // Jika user lama & sudah lengkap, langsung login
        if ($existingUser && $existingUser->phone) {
            // Update google_id & avatar jika belum ada
            $existingUser->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $existingUser->avatar ?? $googleUser->getAvatar(),
            ]);
            Auth::login($existingUser, true);
            return redirect('/dashboard');
        }

        // ✅ User baru atau belum lengkap — simpan data Google ke session
        session([
            'google_email'  => $googleUser->getEmail(),
            'google_name'   => $googleUser->getName(),
            'google_id'     => $googleUser->getId(),
            'google_avatar' => $googleUser->getAvatar(),
        ]);

        return redirect()->route('register')->with('from_google', true);

    } catch (\Exception $e) {
        Log::error('Google Error:', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);
        return redirect()->route('login')->with('error', 'Login Google gagal');
    }
}
}