@extends('layouts.app')
@section('title', 'Reset Password - Bus 88')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 gradient-merah rounded-xl flex items-center justify-center shadow-lg shadow-merah-600/20">
                    <span class="text-white font-black text-xl">88</span>
                </div>
            </a>
            <h1 class="text-2xl font-bold text-dark mb-2">Reset Password</h1>
            <p class="text-gray-warm-500">Masukkan password baru Anda</p>
        </div>

        <div class="card p-8">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
                @endif
                <div>
                    <label class="label-field">Password Baru</label>
                    <input type="password" name="password" class="input-field" placeholder="Minimal 8 karakter" required autofocus>
                </div>
                <div>
                    <label class="label-field">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="input-field" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn-primary w-full text-center">Reset Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
