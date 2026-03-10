@extends('layouts.app')
@section('title', 'Masuk - Bus 88')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 gradient-merah rounded-xl flex items-center justify-center shadow-lg shadow-merah-600/20">
                    <span class="text-white font-black text-xl">88</span>
                </div>
            </a>
            <h1 class="text-2xl font-bold text-dark mb-2">Selamat Datang Kembali</h1>
            <p class="text-gray-warm-500">Masuk ke akun Bus 88 Anda</p>
        </div>

        <div class="card p-8">
            {{-- Google Login Button --}}
            <a href="{{ route('auth.google') }}"
               class="flex items-center justify-center gap-3 w-full border-2 border-gray-warm-200 rounded-xl py-3 px-4 font-semibold text-gray-warm-700 hover:border-merah-300 hover:bg-merah-50 hover:text-merah-700 transition-all duration-200 mb-6 group">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="relative flex items-center gap-4 mb-6">
                <div class="flex-1 h-px bg-gray-warm-100"></div>
                <span class="text-xs text-gray-warm-400 font-medium">atau masuk dengan email</span>
                <div class="flex-1 h-px bg-gray-warm-100"></div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
                @endif
                <div>
                    <label class="label-field">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-field" placeholder="email@contoh.com" required autofocus>
                </div>
                <div>
                    <label class="label-field">Password</label>
                    <input type="password" name="password" class="input-field" placeholder="Masukkan password" required>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-warm-300 text-merah-600 focus:ring-merah-500">
                        <span class="text-sm text-gray-warm-600">Ingat saya</span>
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full text-center">Masuk</button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-warm-500 mt-6">
            Belum punya akun? <a href="{{ route('register') }}" class="text-merah-600 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
