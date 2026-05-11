@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')

{{-- Welcome Header --}}
<div class="mb-8 animate-slide-up">
    <h2 class="text-2xl font-black text-dark tracking-tight">Selamat Datang, {{ auth()->user()->name }} 👋</h2>
    <p class="text-gray-warm-500 mt-1">Ringkasan performa bisnis Bus 88 hari ini.</p>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Revenue --}}
    <div class="stat-card group hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:shadow-lg group-hover:shadow-emerald-600/20 transition-all duration-300">
                <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="badge-success text-[10px]">Aktif</span>
        </div>
        <p class="text-2xl font-black text-dark">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        <p class="text-sm text-gray-warm-500 mt-1">Total Pendapatan</p>
    </div>

    {{-- Bookings --}}
    <div class="stat-card group hover:shadow-lg hover:border-blue-200 transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:shadow-lg group-hover:shadow-blue-600/20 transition-all duration-300">
                <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-black text-dark">{{ $stats['total_bookings'] }}</p>
        <p class="text-sm text-gray-warm-500 mt-1">Total Booking Tiket</p>
    </div>

    {{-- Rentals --}}
    <div class="stat-card group hover:shadow-lg hover:border-amber-200 transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-600 group-hover:shadow-lg group-hover:shadow-amber-600/20 transition-all duration-300">
                <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            @if($stats['pending_rentals'] > 0)
                <span class="badge-warning">{{ $stats['pending_rentals'] }} menunggu</span>
            @endif
        </div>
        <p class="text-2xl font-black text-dark">{{ $stats['total_rentals'] }}</p>
        <p class="text-sm text-gray-warm-500 mt-1">Total Sewa Bus</p>
    </div>

    {{-- Tours --}}
    <div class="stat-card group hover:shadow-lg hover:border-merah-200 transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-merah-100 rounded-xl flex items-center justify-center group-hover:bg-merah-600 group-hover:shadow-lg group-hover:shadow-merah-600/20 transition-all duration-300">
                <svg class="w-6 h-6 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5v.5m-3 6.065V19a2 2 0 01-2-2v-1a2 2 0 00-2-2 2 2 0 01-2-2v-2.945M18 9.874V5a2 2 0 00-2-2h-1.5a2.5 2.5 0 00-2.5 2.5V5a2 2 0 012 2h1.5a2.5 2.5 0 012.5 2.5z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-black text-dark">{{ $stats['total_tours'] }}</p>
        <p class="text-sm text-gray-warm-500 mt-1">Total Paket Wisata</p>
    </div>
</div>

{{-- Tables Grid --}}
<div class="grid lg:grid-cols-2 gap-6">
    {{-- Recent Bookings --}}
    <div class="table-container hover:shadow-md transition-shadow duration-300">
        <div class="px-6 py-4 border-b border-gray-warm-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <h3 class="font-bold text-dark">Booking Terbaru</h3>
            </div>
            <a href="{{ route('admin.transactions.bookings') }}" class="text-xs font-bold text-merah-600 hover:text-merah-700 hover:underline transition-colors">Lihat Semua &rarr;</a>
        </div>
        @if($recentBookings->isEmpty())
            <div class="p-12 text-center">
                <div class="w-14 h-14 bg-gray-warm-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-warm-500">Belum ada booking masuk.</p>
                <p class="text-xs text-gray-warm-400 mt-1">Data akan muncul saat pelanggan mulai memesan.</p>
            </div>
        @else
            <table class="w-full">
                <thead><tr><th class="table-header">Kode</th><th class="table-header">Rute</th><th class="table-header">Status</th><th class="table-header">Total</th></tr></thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50 transition-colors duration-150">
                        <td class="table-cell font-semibold text-dark text-xs tracking-wider">{{ $booking->booking_code }}</td>
                        <td class="table-cell text-xs">{{ $booking->schedule->route->origin }} &rarr; {{ $booking->schedule->route->destination }}</td>
                        <td class="table-cell">
                            <span class="{{ match($booking->payment_status) { 'paid' => 'badge-success', 'pending' => 'badge-warning', default => 'badge-gray' } }}">{{ $booking->payment_status }}</span>
                        </td>
                        <td class="table-cell font-semibold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Recent Rentals --}}
    <div class="table-container hover:shadow-md transition-shadow duration-300">
        <div class="px-6 py-4 border-b border-gray-warm-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="font-bold text-dark">Sewa Terbaru</h3>
            </div>
            <a href="{{ route('admin.transactions.rentals') }}" class="text-xs font-bold text-merah-600 hover:text-merah-700 hover:underline transition-colors">Lihat Semua &rarr;</a>
        </div>
        @if($recentRentals->isEmpty())
            <div class="p-12 text-center">
                <div class="w-14 h-14 bg-gray-warm-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-warm-500">Belum ada sewa masuk.</p>
                <p class="text-xs text-gray-warm-400 mt-1">Data akan muncul saat pelanggan mengajukan sewa.</p>
            </div>
        @else
            <table class="w-full">
                <thead><tr><th class="table-header">Kode</th><th class="table-header">Tujuan</th><th class="table-header">Status</th><th class="table-header">Total</th></tr></thead>
                <tbody>
                    @foreach($recentRentals as $rental)
                    <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50 transition-colors duration-150">
                        <td class="table-cell font-semibold text-dark text-xs tracking-wider">{{ $rental->rental_code }}</td>
                        <td class="table-cell text-xs">{{ $rental->destination }}</td>
                        <td class="table-cell">
                            <span class="{{ match($rental->approval_status) { 'approved' => 'badge-success', 'pending' => 'badge-warning', default => 'badge-danger' } }}">{{ $rental->approval_status }}</span>
                        </td>
                        <td class="table-cell font-semibold">{{ $rental->total_price ? 'Rp '.number_format($rental->total_price,0,',','.') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Quick Stats --}}
<div class="grid sm:grid-cols-3 gap-6 mt-6">
    <div class="stat-card flex items-center gap-4 group hover:border-merah-200 hover:shadow-md transition-all duration-300">
        <div class="w-12 h-12 bg-merah-100 rounded-xl flex items-center justify-center group-hover:bg-merah-600 transition-all duration-300">
            <svg class="w-6 h-6 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div>
            <p class="text-xl font-black text-dark">{{ $stats['active_buses'] }}<span class="text-gray-warm-400 font-medium text-sm">/{{ $stats['total_buses'] }}</span></p>
            <p class="text-sm text-gray-warm-500">Bus Aktif</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4 group hover:border-blue-200 hover:shadow-md transition-all duration-300">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-all duration-300">
            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        </div>
        <div>
            <p class="text-xl font-black text-dark">{{ $stats['total_routes'] }}</p>
            <p class="text-sm text-gray-warm-500">Rute Tersedia</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4 group hover:border-emerald-200 hover:shadow-md transition-all duration-300">
        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 transition-all duration-300">
            <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div>
            <p class="text-xl font-black text-dark">Midtrans</p>
            <p class="text-sm text-gray-warm-500">Payment Gateway</p>
        </div>
    </div>
</div>
@endsection
