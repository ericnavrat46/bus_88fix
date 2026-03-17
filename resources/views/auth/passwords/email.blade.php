@extends('layouts.app')
@section('title', 'Lupa Password - Bus 88')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 gradient-merah rounded-xl flex items-center justify-center shadow-lg shadow-merah-600/20">
                    <span class="text-white font-black text-xl">88</span>
                </div>
            </a>
            <h1 class="text-2xl font-bold text-dark mb-2">Lupa Password</h1>
            <p class="text-gray-warm-500">Masukkan email Anda untuk menerima kode OTP</p>
        </div>

        <div class="card p-8">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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
                <button type="submit" class="btn-primary w-full text-center">Kirim Kode OTP</button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-warm-500 mt-6">
            Kembali ke <a href="{{ route('login') }}" class="text-merah-600 font-semibold hover:underline">Halaman Masuk</a>
        </p>
    </div>
</div>
@endsection
