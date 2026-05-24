@extends('layouts.app')
@section('title', 'Dashboard - Bus 88')

@push('styles')
<style>
    :root {
        --primary: #cc0000;
        --primary-dark: #990000;
        --bg-gray: #f8fafc;
    }
    body { background-color: var(--bg-gray); font-family: 'Plus Jakarta Sans', sans-serif; }
    .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); }
    .btn-quick { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .btn-quick:hover { transform: translateY(-4px); box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1); }
    .loyalty-card { background: linear-gradient(135deg, #cc0000 0%, #800000 100%); position: relative; overflow: hidden; }
    .loyalty-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .upcoming-card { transition: all 0.3s ease; }
    .upcoming-card:hover { transform: scale(1.01); }
    .status-badge { padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .status-paid { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef9c3; color: #854d0e; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }
    .status-expired { background: #f1f5f9; color: #475569; }
    
    /* Scrollbar hide */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .fab-btn { width: 56px; height: 56px; border-radius: 28px; background: var(--primary); display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 8px 16px rgba(204, 0, 0, 0.3); transition: all 0.3s ease; }
    .fab-btn:hover { transform: scale(1.1) rotate(90deg); background: var(--primary-dark); }

    /* Custom form styling */
    .form-section { display: none; }
    .form-section.show { display: block; animation: slideDown 0.4s ease-out; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
</style>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
@php
    $upcomingTrip = $bookings->where('payment_status', 'paid')
        ->sortBy('schedule.departure_date')
        ->first();

    $busActivities = $bookings->map(fn($b) => [
        'type' => 'Tiket Bus',
        'icon' => '🎫',
        'id' => $b->booking_code,
        'date' => $b->created_at,
        'status' => $b->payment_status,
        'title' => $b->schedule->route->origin . ' - ' . $b->schedule->route->destination,
        'url' => route('dashboard.booking', $b),
        'download_url' => $b->payment_status === 'paid' ? route('ticket.bus.download', $b) : null,
    ]);

    $rentalActivities = $rentals->map(fn($r) => [
        'type' => 'Sewa Bus',
        'icon' => '🚌',
        'id' => $r->rental_code,
        'date' => $r->created_at,
        'status' => $r->payment_status,
        'title' => $r->pickup_location . ' - ' . $r->destination,
        'url' => route('dashboard.rental', $r),
        'download_url' => $r->payment_status === 'paid' ? route('ticket.rental.download', $r) : null,
    ]);

    $tourActivities = $tourBookings->map(fn($t) => [
        'type' => 'Paket Tur',
        'icon' => '🗺️',
        'id' => $t->booking_code,
        'date' => $t->created_at,
        'status' => $t->payment_status,
        'title' => $t->tourPackage->name ?? 'Paket Wisata',
        'url' => route('dashboard.tour', $t),
        'download_url' => $t->payment_status === 'paid' ? route('ticket.tour.download', $t) : null,
    ]);

    $refundActivities = $refunds->map(fn($ref) => [
        'type' => 'Refund',
        'icon' => '💰',
        'id' => $ref->refund_code,
        'date' => $ref->created_at,
        'status' => $ref->status, // pending, approved, rejected, completed
        'title' => 'Refund: ' . ($ref->booking->schedule->route->origin ?? '') . ' - ' . ($ref->booking->schedule->route->destination ?? ''),
        'url' => route('dashboard.booking', $ref->booking_id),
        'download_url' => null,
    ]);

    $allActivities = collect()
        ->concat($busActivities)
        ->concat($rentalActivities)
        ->concat($tourActivities)
        ->concat($refundActivities)
        ->sortByDesc('date')
        ->take(10);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Greeting Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">
                Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋 Siap untuk petualangan berikutnya?
            </h1>
            <p class="text-slate-500">Temukan rute terbaik dan nikmati perjalanan yang nyaman bersama kami.</p>
        </div>
        <div class="flex items-center gap-3">
             <button onclick="toggleEditProfile()" class="p-2.5 bg-white rounded-xl shadow-sm border border-slate-200 text-slate-600 hover:text-red-600 transition-colors" title="Edit Profil">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </button>
            <button onclick="toggleChangePassword()" class="p-2.5 bg-white rounded-xl shadow-sm border border-slate-200 text-slate-600 hover:text-red-600 transition-colors" title="Ubah Password">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </button>
        </div>
    </div>

    {{-- Hidden Forms (integrated from old design) --}}
    <div id="editProfileContainer">
        <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="form-section mb-12 p-8 bg-white rounded-3xl shadow-xl border border-slate-100 relative z-10">
            @csrf
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">Update Profil</h3>
                <button type="button" onclick="toggleEditProfile()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-slate-700">Telepon</label>
                    <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-slate-700">Alamat</label>
                    <input type="text" name="address" value="{{ auth()->user()->address }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                </div>
                <div class="md:col-span-2 space-y-1">
                    <label class="text-sm font-semibold text-slate-700">Foto Profil</label>
                    <input type="file" name="avatar" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-all">
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="toggleEditProfile()" class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all">Batal</button>
                <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all">Simpan Perubahan</button>
            </div>
        </form>

        <div id="otpSection" class="form-section mb-12 p-8 bg-white rounded-3xl shadow-xl border border-slate-100 relative z-10">
             {{-- Step 1 --}}
             <div id="step1SendOtp">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Ubah Password (OTP)</h3>
                    <button type="button" onclick="toggleChangePassword()" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-slate-500 mb-6 text-sm">Demi keamanan, kami akan mengirimkan kode OTP ke email <strong>{{ auth()->user()->email }}</strong>.</p>
                <button type="button" id="sendOtpBtn" class="w-full md:w-auto px-8 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all flex items-center justify-center gap-2">
                    <div class="bg-white p-1 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#EA4335" d="M502.3 190.8L327.4 338.7V512h112.4c31 0 56.2-25.1 56.2-56.2V190.8z"/>
                            <path fill="#34A853" d="M0 190.8v265c0 31 25.1 56.2 56.2 56.2h112.4V338.7L0 190.8z"/>
                            <path fill="#FBBC04" d="M168.6 338.7l87.4 65.6 87.4-65.6V190.8L256 256 168.6 190.8z"/>
                            <path fill="#4285F4" d="M502.3 112.4c-4.2-15.8-18.5-28-35.3-28H45c-16.8 0-31.1 12.2-35.3 28L256 300.6 502.3 112.4z"/>
                        </svg>
                    </div>
                    Kirim OTP
                </button>
                <div id="timerDisplay" class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100 text-amber-700 text-sm hidden">
                    ⏳ Harap tunggu <span id="countdown">60</span> detik sebelum mengirim ulang OTP
                </div>
            </div>
            {{-- Step 2 & 3 will be handled by existing script logic --}}
            <div id="step2VerifyOtp" style="display: none;">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Verifikasi OTP</h3>
                <div class="p-4 bg-blue-50 text-blue-700 rounded-xl text-sm mb-6">Kode OTP telah dikirim. Masukkan 6 digit di bawah ini.</div>
                <input type="text" id="otpCode" class="w-full max-w-xs px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-center text-3xl font-black tracking-[1rem] focus:ring-2 focus:ring-red-500 outline-none mb-6" placeholder="000000" maxlength="6">
                <div class="flex gap-3">
                    <button type="button" id="verifyOtpBtn" class="px-8 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all">Verifikasi</button>
                    <button type="button" id="backToSendBtn" class="px-8 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all">Kirim Ulang</button>
                </div>
            </div>
            <div id="step3ChangePassword" style="display: none;">
                <h3 class="text-xl font-bold text-green-600 mb-4">✅ Verifikasi Berhasil</h3>
                <p class="text-slate-500 mb-6 text-sm">Silakan masukkan password baru Anda.</p>
                <div class="grid gap-4 mb-6">
                    <input type="password" id="newPassword" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" placeholder="Password Baru">
                    <input type="password" id="confirmPassword" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" placeholder="Konfirmasi Password Baru">
                </div>
                <button type="button" id="changePasswordBtn" class="px-8 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all">Simpan Password</button>
            </div>
        </form>
    </div>

    {{-- Quick Action Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
        <a href="{{ route('home') }}#search" class="btn-quick group bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-600 group-hover:text-white transition-all">
                <svg class="w-7 h-7 text-red-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <span class="font-bold text-slate-800">Pesan Tiket Bus</span>
        </a>
        <a href="{{ route('rental.index') }}" class="btn-quick group bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-600 group-hover:text-white transition-all">
                <svg class="w-7 h-7 text-red-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <span class="font-bold text-slate-800">Sewa Bus</span>
        </a>
        <a href="{{ route('tour.index') }}" class="btn-quick group bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-600 group-hover:text-white transition-all">
                <svg class="w-7 h-7 text-red-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a1.5 1.5 0 011.5 1.5v.5a2.5 2.5 0 01-2.5 2.5H14"></path></svg>
            </div>
            <span class="font-bold text-slate-800">Paket Tur</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-10">
            {{-- Upcoming Journey --}}
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Perjalanan Mendatang</h2>
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-red-600 hover:underline">Lihat Semua</a>
                </div>
                
                @if($upcomingTrip)
                <div class="upcoming-card glass-card rounded-[2rem] overflow-hidden shadow-xl border-slate-100 flex flex-col">
                    <div class="relative h-48 md:h-64 overflow-hidden">
                        <img src="{{ asset('images/bg.png') }}" class="w-full h-full object-cover" alt="Bus">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <div class="absolute top-4 left-6">
                            <span class="status-badge bg-red-600 text-white shadow-lg">{{ $upcomingTrip->schedule->bus->class ?? 'EKSEKUTIF' }}</span>
                        </div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="flex items-center gap-4 text-white">
                                <h3 class="text-2xl md:text-3xl font-black">{{ $upcomingTrip->schedule->route->origin }}</h3>
                                <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                <h3 class="text-2xl md:text-3xl font-black">{{ $upcomingTrip->schedule->route->destination }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 bg-white flex flex-wrap items-center justify-between gap-6">
                        <div class="grid grid-cols-3 gap-10">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Tanggal</p>
                                <p class="text-sm font-bold text-red-600">{{ $upcomingTrip->schedule->departure_date->translatedFormat('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Waktu</p>
                                <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($upcomingTrip->schedule->departure_time)->format('H:i') }} WIB</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Kursi</p>
                                <p class="text-sm font-bold text-slate-800">
                                    {{ $upcomingTrip->passengers->first()->seat_number ?? '-' }}
                                    @if($upcomingTrip->total_seats > 1)
                                        <span class="text-[10px] text-slate-400 font-medium">+{{ $upcomingTrip->total_seats - 1 }} lagi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.booking', $upcomingTrip) }}" class="flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            E-Tiket
                        </a>
                    </div>
                </div>
                @else
                <div class="bg-white p-10 rounded-3xl border border-dashed border-slate-300 text-center">
                    <p class="text-slate-500 font-medium">Belum ada perjalanan mendatang.</p>
                    <a href="{{ route('home') }}#search" class="inline-block mt-4 text-red-600 font-bold hover:underline">Cari Tiket Sekarang →</a>
                </div>
                @endif
            </section>

            {{-- Recent Activities Grouped --}}
            <section x-data="{ activeTab: 'all' }">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <h2 class="text-2xl font-black text-slate-900">Aktivitas Terakhir</h2>
                    <div class="flex bg-slate-100 p-1.5 rounded-2xl overflow-x-auto no-scrollbar">
                        <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-white shadow-md text-red-600' : 'text-slate-500 hover:text-slate-700'" class="px-5 py-2 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Semua</button>
                        <button @click="activeTab = 'bus'" :class="activeTab === 'bus' ? 'bg-white shadow-md text-red-600' : 'text-slate-500 hover:text-slate-700'" class="px-5 py-2 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Tiket Bus</button>
                        <button @click="activeTab = 'rental'" :class="activeTab === 'rental' ? 'bg-white shadow-md text-red-600' : 'text-slate-500 hover:text-slate-700'" class="px-5 py-2 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Sewa Bus</button>
                        <button @click="activeTab = 'tour'" :class="activeTab === 'tour' ? 'bg-white shadow-md text-red-600' : 'text-slate-500 hover:text-slate-700'" class="px-5 py-2 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Paket Wisata</button>
                        <button @click="activeTab = 'refund'" :class="activeTab === 'refund' ? 'bg-white shadow-md text-red-600' : 'text-slate-500 hover:text-slate-700'" class="px-5 py-2 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Refund</button>
                    </div>
                </div>

                {{-- All Tab --}}
                <div x-show="activeTab === 'all'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    @forelse($allActivities as $activity)
                        @include('dashboard.partials.activity-item', ['activity' => $activity])
                    @empty
                        <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200">
                            <p class="text-5xl mb-3">📭</p>
                            <p class="text-slate-400 italic text-sm">Belum ada riwayat aktivitas.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Bus Tab --}}
                <div x-show="activeTab === 'bus'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display:none" class="space-y-4">
                    @forelse($busActivities as $activity)
                        @include('dashboard.partials.activity-item', ['activity' => $activity])
                    @empty
                        <div class="text-center py-8 bg-white rounded-3xl border border-dashed border-slate-200">
                            <p class="text-3xl mb-2">🎫</p>
                            <p class="text-slate-400 italic text-sm">Belum ada riwayat tiket bus.</p>
                        </div>
                    @endforelse
                    @if($busActivities->count() >= 5)
                    <div class="pt-2">
                        <a href="{{ route('dashboard.history', 'bus') }}" class="block w-full py-3 text-center bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">Lihat Semua Riwayat Tiket</a>
                    </div>
                    @endif
                </div>

                {{-- Rental Tab --}}
                <div x-show="activeTab === 'rental'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display:none" class="space-y-4">
                    @forelse($rentalActivities as $activity)
                        @include('dashboard.partials.activity-item', ['activity' => $activity])
                    @empty
                        <div class="text-center py-8 bg-white rounded-3xl border border-dashed border-slate-200">
                            <p class="text-3xl mb-2">🚌</p>
                            <p class="text-slate-400 italic text-sm">Belum ada riwayat sewa bus.</p>
                        </div>
                    @endforelse
                    @if($rentalActivities->count() >= 5)
                    <div class="pt-2">
                        <a href="{{ route('dashboard.history', 'rental') }}" class="block w-full py-3 text-center bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">Lihat Semua Riwayat Sewa</a>
                    </div>
                    @endif
                </div>

                {{-- Tour Tab --}}
                <div x-show="activeTab === 'tour'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display:none" class="space-y-4">
                    @forelse($tourActivities as $activity)
                        @include('dashboard.partials.activity-item', ['activity' => $activity])
                    @empty
                        <div class="text-center py-8 bg-white rounded-3xl border border-dashed border-slate-200">
                            <p class="text-3xl mb-2">🗺️</p>
                            <p class="text-slate-400 italic text-sm">Belum ada riwayat paket wisata.</p>
                        </div>
                    @endforelse
                    @if($tourActivities->count() >= 5)
                    <div class="pt-2">
                        <a href="{{ route('dashboard.history', 'tour') }}" class="block w-full py-3 text-center bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">Lihat Semua Riwayat Paket Tur</a>
                    </div>
                    @endif
                </div>
                {{-- Refund Tab --}}
                <div x-show="activeTab === 'refund'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display:none" class="space-y-4">
                    @forelse($refundActivities as $activity)
                        @include('dashboard.partials.activity-item', ['activity' => $activity])
                    @empty
                        <div class="text-center py-8 bg-white rounded-3xl border border-dashed border-slate-200">
                            <p class="text-3xl mb-2">💰</p>
                            <p class="text-slate-400 italic text-sm">Belum ada riwayat refund.</p>
                        </div>
                    @endforelse
                    @if($refundActivities->count() >= 5)
                    <div class="pt-2">
                        <a href="{{ route('dashboard.history', 'refund') }}" class="block w-full py-3 text-center bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">Lihat Semua Riwayat Refund</a>
                    </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- Sidebar Column --}}
        <div class="space-y-8">
            {{-- Pusat Bantuan Card (Fixed Light Theme) --}}
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full blur-2xl group-hover:bg-red-100 transition-all"></div>
                <div class="relative z-10">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-red-600 mb-1">Layanan 24/7</p>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Butuh Bantuan?</h3>
                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">Tim support kami siap membantu perjalanan atau pesanan Anda kapan saja.</p>
                    
                    @php
                        $waMessage = "Halo CS Bus 88, saya " . auth()->user()->name . ". Saya ingin bertanya mengenai detail pesanan saya.";
                        $waLink = "https://wa.me/6285784898590?text=" . urlencode($waMessage);
                    @endphp
                    <a href="{{ $waLink }}" target="_blank" class="flex items-center justify-center gap-3 w-full py-4 bg-emerald-600 text-white font-extrabold rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Hubungi Kami
                    </a>
                </div>
            </div>

            {{-- Limited Promo --}}
            <div class="bg-blue-50/50 rounded-3xl p-6 border border-blue-100/50">
                <h4 class="text-sm font-bold text-slate-800 mb-4">Promo Terbatas</h4>
                <div class="space-y-4">
                    @forelse($promoBanners as $promo)
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <h5 class="text-sm font-bold text-red-600 mb-1">{{ $promo->title }}</h5>
                        <p class="text-xs text-slate-500 line-clamp-2">{{ $promo->description }}</p>
                        <div class="mt-3 w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                            <div class="bg-red-500 h-full" style="width: {{ rand(40, 90) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">Tidak ada promo aktif.</p>
                    @endforelse
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
                    <p class="text-xs font-bold text-slate-400 mb-1">Trip Selesai</p>
                    <p class="text-2xl font-black text-red-600">{{ $tripSelesaiCount }}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
                    <p class="text-xs font-bold text-slate-400 mb-1">Kota Dikunjungi</p>
                    <p class="text-2xl font-black text-slate-800">{{ $kotaDikunjungiCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FAB --}}
<div class="fixed bottom-10 right-10 z-50">
    <a href="{{ route('home') }}#search" class="fab-btn">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
    </a>
</div>

@push('scripts')
<script>
// Toggle Functions
function toggleEditProfile() {
    const form = document.getElementById('editProfileForm');
    const otpSection = document.getElementById('otpSection');
    if(otpSection.classList.contains('show')) otpSection.classList.remove('show');
    form.classList.toggle('show');
    if(form.classList.contains('show')) form.scrollIntoView({behavior: 'smooth', block: 'center'});
}

function toggleChangePassword() {
    const otpSection = document.getElementById('otpSection');
    const form = document.getElementById('editProfileForm');
    if(form.classList.contains('show')) form.classList.remove('show');
    otpSection.classList.toggle('show');
    if(otpSection.classList.contains('show')) otpSection.scrollIntoView({behavior: 'smooth', block: 'center'});
}

// Logic placeholder for OTP scripts (re-using the logic from the old file)
let countdownInterval = null;
function startCooldown(seconds) {
    const timerDisplay = document.getElementById('timerDisplay');
    const countdownSpan = document.getElementById('countdown');
    const sendBtn = document.getElementById('sendOtpBtn');
    
    timerDisplay.classList.remove('hidden');
    sendBtn.disabled = true;
    let remaining = seconds;
    
    countdownInterval = setInterval(() => {
        remaining--;
        countdownSpan.textContent = remaining;
        if(remaining <= 0) {
            clearInterval(countdownInterval);
            timerDisplay.classList.add('hidden');
            sendBtn.disabled = false;
        }
    }, 1000);
}

document.getElementById('sendOtpBtn')?.addEventListener('click', async function() {
    this.disabled = true;
    this.innerText = 'Mengirim...';
    try {
        const res = await fetch('{{ route("password.otp.send") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });
        const data = await res.json();
        if(data.success) {
            document.getElementById('step1SendOtp').style.display = 'none';
            document.getElementById('step2VerifyOtp').style.display = 'block';
            startCooldown(60);
        } else {
            alert(data.message);
            this.disabled = false;
            this.innerHTML = '<div class="bg-white p-1 rounded-lg flex items-center justify-center mr-2"><svg class="w-5 h-5" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="#EA4335" d="M502.3 190.8L327.4 338.7V512h112.4c31 0 56.2-25.1 56.2-56.2V190.8z"/><path fill="#34A853" d="M0 190.8v265c0 31 25.1 56.2 56.2 56.2h112.4V338.7L0 190.8z"/><path fill="#FBBC04" d="M168.6 338.7l87.4 65.6 87.4-65.6V190.8L256 256 168.6 190.8z"/><path fill="#4285F4" d="M502.3 112.4c-4.2-15.8-18.5-28-35.3-28H45c-16.8 0-31.1 12.2-35.3 28L256 300.6 502.3 112.4z"/></svg></div> Kirim OTP';
        }
    } catch(e) {
        this.innerHTML = '<div class="bg-white p-1 rounded-lg flex items-center justify-center mr-2"><svg class="w-5 h-5" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="#EA4335" d="M502.3 190.8L327.4 338.7V512h112.4c31 0 56.2-25.1 56.2-56.2V190.8z"/><path fill="#34A853" d="M0 190.8v265c0 31 25.1 56.2 56.2 56.2h112.4V338.7L0 190.8z"/><path fill="#FBBC04" d="M168.6 338.7l87.4 65.6 87.4-65.6V190.8L256 256 168.6 190.8z"/><path fill="#4285F4" d="M502.3 112.4c-4.2-15.8-18.5-28-35.3-28H45c-16.8 0-31.1 12.2-35.3 28L256 300.6 502.3 112.4z"/></svg></div> Kirim OTP';
    }
});

document.getElementById('verifyOtpBtn')?.addEventListener('click', async function() {
    const otp = document.getElementById('otpCode').value;
    try {
        const res = await fetch('{{ route("password.otp.verify.only") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({otp})
        });
        const data = await res.json();
        if(data.success) {
            document.getElementById('step2VerifyOtp').style.display = 'none';
            document.getElementById('step3ChangePassword').style.display = 'block';
        } else {
            alert(data.message);
        }
    } catch(e) {}
});

document.getElementById('changePasswordBtn')?.addEventListener('click', async function() {
    const password = document.getElementById('newPassword').value;
    const password_confirmation = document.getElementById('confirmPassword').value;
    try {
        const res = await fetch('{{ route("password.change") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({password, password_confirmation})
        });
        const data = await res.json();
        if(data.success) {
            alert('Password berhasil diubah. Silakan login kembali.');
            window.location.reload();
        } else {
            alert(data.message);
        }
    } catch(e) {}
});
</script>
@endpush
@endsection