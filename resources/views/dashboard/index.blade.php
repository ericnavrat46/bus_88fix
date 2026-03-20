@extends('layouts.app')
@section('title', 'Dashboard - Bus 88')
@push('styles')
<style>
    /* Dashboard card hover */
    .dash-card {
        transition: transform 0.3s cubic-bezier(.22,.68,0,1.2), box-shadow 0.3s ease, border-color 0.3s ease;
        border: 1.5px solid transparent;
    }
    .dash-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 44px rgba(204,0,0,0.1), 0 6px 16px rgba(0,0,0,0.07);
        border-color: rgba(204,0,0,0.12);
    }
    .dash-card:hover .dash-code {
        color: #b80000;
    }
    /* Detail link arrow slide */
    .detail-link {
        transition: gap 0.2s ease, color 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .detail-link:hover {
        gap: 8px;
        color: #b80000;
    }
    /* Top action buttons */
    .dash-btn {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .dash-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(204,0,0,0.25);
    }
    .dash-btn-sec:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
</style>
@endpush
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black text-dark mb-1">Dashboard Saya</h1>
                <p class="text-gray-warm-500">Selamat datang, {{ auth()->user()->name }}!</p>
            </div>
            <div class="flex gap-3 mt-4 md:mt-0">
                <a href="{{ route('home') }}#search" class="btn-primary btn-sm dash-btn">Beli Tiket</a>
                <a href="{{ route('rental.index') }}" class="btn-secondary btn-sm dash-btn dash-btn-sec">Sewa Bus</a>
            </div>
        </div>

        {{-- Booking History --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold text-dark mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                Riwayat Tiket
            </h2>
            @if($bookings->isEmpty())
            <div class="card p-8 text-center">
                <p class="text-gray-warm-500 mb-4">Belum ada pesanan tiket.</p>
                <a href="{{ route('home') }}" class="btn-primary btn-sm">Cari Tiket Sekarang</a>
            </div>
            @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                <div class="card-premium p-6 dash-card">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-sm font-bold text-merah-600 tracking-wider dash-code">{{ $booking->booking_code }}</span>
                                @php
                                    $statusClass = match($booking->payment_status) {
                                        'paid' => 'badge-success',
                                        'pending' => 'badge-warning',
                                        'expired' => 'badge-gray',
                                        'cancelled' => 'badge-danger',
                                        'refunded' => 'badge-info',
                                        default => 'badge-gray',
                                    };
                                    $statusLabel = match($booking->payment_status) {
                                        'paid' => 'Lunas',
                                        'pending' => 'Menunggu Bayar',
                                        'expired' => 'Kedaluwarsa',
                                        'cancelled' => 'Dibatalkan',
                                        'refunded' => 'Refund',
                                        default => $booking->payment_status,
                                    };
                                @endphp
                                <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                            <p class="font-semibold text-dark">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</p>
                            <p class="text-sm text-gray-warm-500">{{ $booking->schedule->departure_date->translatedFormat('d M Y') }} · {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }} · {{ $booking->schedule->bus->name }}</p>
                            <p class="text-sm text-gray-warm-500">{{ $booking->total_seats }} kursi</p>
                            @if($booking->payment_status === 'pending' && $booking->expired_at)
                            <p class="text-xs text-amber-600 font-bold mt-1 inline-flex items-center gap-1">
                                <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Bayar sebelum {{ $booking->expired_at->format('H:i') }} WIB
                            </p>
                            @endif
                        </div>
                        <div class="text-right flex flex-col items-end gap-2">
                            <p class="text-xl font-black text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            @if($booking->payment_status === 'pending' && $booking->snap_token)
                            <a href="{{ route('booking.checkout', $booking) }}" class="btn-primary btn-sm">Bayar</a>
                            @endif
                            <a href="{{ route('dashboard.booking', $booking) }}" class="text-sm text-merah-600 font-medium detail-link">Detail <span>→</span></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{ $bookings->links() }}
            @endif
        </div>

        {{-- Rental History --}}
        <div>
            <h2 class="text-xl font-bold text-dark mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Riwayat Sewa Bus
            </h2>
            @if($rentals->isEmpty())
            <div class="card p-8 text-center">
                <p class="text-gray-warm-500 mb-4">Belum ada sewa bus.</p>
                <a href="{{ route('rental.index') }}" class="btn-primary btn-sm">Sewa Bus</a>
            </div>
            @else
            <div class="space-y-4">
                @foreach($rentals as $rental)
                <div class="card-premium p-6 dash-card">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-sm font-bold text-merah-600 tracking-wider dash-code">{{ $rental->rental_code }}</span>
                                @php
                                    $approvalClass = match($rental->approval_status) {
                                        'approved' => 'badge-success',
                                        'pending' => 'badge-warning',
                                        'rejected' => 'badge-danger',
                                    };
                                @endphp
                                <span class="{{ $approvalClass }}">{{ ucfirst($rental->approval_status) }}</span>
                            </div>
                            <p class="font-semibold text-dark">{{ $rental->pickup_location }} → {{ $rental->destination }}</p>
                            <p class="text-sm text-gray-warm-500">{{ $rental->start_date->translatedFormat('d M Y') }} - {{ $rental->end_date->translatedFormat('d M Y') }} ({{ $rental->duration_days }} hari)</p>
                            @if($rental->bus)
                            <p class="text-sm text-gray-warm-500">Bus: {{ $rental->bus->name }}</p>
                            @endif
                            @if($rental->approval_status === 'approved' && in_array($rental->payment_status, ['unpaid', 'pending']))
                            <p class="text-xs text-amber-600 font-bold mt-1 inline-flex items-center gap-1">
                                <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Bayar sebelum {{ $rental->updated_at->addHours(2)->format('H:i') }} WIB
                            </p>
                            @endif
                        </div>
                        <div class="text-right flex flex-col items-end gap-2">
                            @if($rental->total_price)
                            <p class="text-xl font-black text-merah-600">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                            @else
                            <p class="text-sm text-gray-warm-400">Harga belum ditentukan</p>
                            @endif
                            @if($rental->approval_status === 'approved' && $rental->payment_status !== 'paid')
                            <a href="{{ route('rental.pay', $rental) }}" class="btn-primary btn-sm">Bayar</a>
                            @endif
                            <a href="{{ route('dashboard.rental', $rental) }}" class="text-sm text-merah-600 font-medium detail-link">Detail Sewa <span>→</span></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{ $rentals->links() }}
            @endif
        </div>
        {{-- Tour Package History --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold text-dark mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5v.5m-3 6.065V19a2 2 0 01-2-2v-1a2 2 0 00-2-2 2 2 0 01-2-2v-2.945M18 9.874V5a2 2 0 00-2-2h-1.5a2.5 2.5 0 00-2.5 2.5V5a2 2 0 012 2h1.5a2.5 2.5 0 012.5 2.5z"/></svg>
                Riwayat Paket Wisata
            </h2>
            @if($tourBookings->isEmpty())
            <div class="card p-8 text-center bg-white">
                <p class="text-gray-warm-500 mb-4">Belum ada pesanan paket wisata.</p>
                <a href="{{ route('tour.index') }}" class="btn-primary btn-sm">Cari Paket Wisata</a>
            </div>
            @else
            <div class="space-y-4">
                @foreach($tourBookings as $tBooking)
                <div class="card-premium p-6 dash-card">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-sm font-bold text-merah-600 tracking-wider dash-code">{{ $tBooking->booking_code }}</span>
                                @php
                                    $tStatusClass = match($tBooking->payment_status) {
                                        'paid' => 'badge-success',
                                        'pending' => 'badge-warning',
                                        default => 'badge-gray',
                                    };
                                @endphp
                                <span class="{{ $tStatusClass }}">{{ ucfirst($tBooking->payment_status) }}</span>
                            </div>
                            <p class="font-semibold text-dark">{{ $tBooking->tourPackage->name ?? 'Paket Tidak Tersedia' }}</p>
                            <p class="text-sm text-gray-warm-500">Berangkat: {{ $tBooking->travel_date ? $tBooking->travel_date->format('d M Y') : '-' }} · {{ $tBooking->passenger_count }} Orang</p>
                            @if($tBooking->payment_status === 'pending')
                            <p class="text-xs text-amber-600 font-bold mt-1 inline-flex items-center gap-1">
                                <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Bayar sebelum {{ $tBooking->created_at->addHours(2)->format('H:i') }} WIB
                            </p>
                            @endif
                        </div>
                        <div class="text-right flex flex-col items-end gap-2">
                            <p class="text-xl font-black text-merah-600">Rp {{ number_format($tBooking->total_price, 0, ',', '.') }}</p>
                            @if($tBooking->payment_status === 'pending')
                            <a href="{{ route('tour.checkout', $tBooking) }}" class="btn-primary btn-sm">Bayar</a>
                            @endif
                            <a href="{{ route('dashboard.tour', $tBooking) }}" class="text-sm text-merah-600 font-medium detail-link">Detail Paket <span>→</span></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $tourBookings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
