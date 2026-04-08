<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (auth()->user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users',
        'phone'    => 'required|string|max:20',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $googleId    = session('google_id');
    $googleAvatar = session('google_avatar');

    $user = User::create([
        'name'      => $validated['name'],
        'email'     => $validated['email'],
        'phone'     => $validated['phone'],
        'password'  => Hash::make($validated['password']),
        'role'      => 'customer',
        'google_id' => $googleId ?? null,
        'avatar'    => $googleAvatar ?? null,
    ]);

    // Bersihkan session Google
    session()->forget(['google_email', 'google_name', 'google_id', 'google_avatar']);

    Auth::login($user);
    return redirect('/dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
