@extends('layouts.app')
@section('title', 'Verifikasi OTP - Bus 88')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 gradient-merah rounded-xl flex items-center justify-center shadow-lg shadow-merah-600/20">
                    <span class="text-white font-black text-xl">88</span>
                </div>
            </a>
            <h1 class="text-2xl font-bold text-dark mb-2">Verifikasi OTP</h1>
            <p class="text-gray-warm-500">Masukkan 6 digit kode yang dikirim ke email Anda</p>
        </div>

        <div class="card p-8">
            @if (session('status'))
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl text-sm mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-5">
                @csrf
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
                @endif
                <div>
                    <label class="label-field">Kode OTP</label>
                    <input type="text" name="otp" class="input-field text-center text-2xl tracking-widest" placeholder="123456" maxlength="6" required autofocus>
                </div>
                <button type="submit" class="btn-primary w-full text-center">Verifikasi OTP</button>
            </form>

            <form method="POST" action="{{ route('password.email') }}" class="mt-4">
                @csrf
                <input type="hidden" name="email" value="{{ session('reset_email') }}">
                <button type="submit" class="w-full text-center text-sm text-merah-600 font-semibold hover:underline">
                    Kirim ulang kode?
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
