@extends('layouts.app')
@section('title', "Jadwal Bus {$origin} - {$destination}")
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header --}}
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-gray-warm-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-merah-600 transition-colors">Beranda</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-dark font-medium">Hasil Pencarian</span>
            </nav>

            {{-- Trip Type Badge --}}
            <div class="flex items-center gap-3 mb-3">
                <h1 class="text-3xl font-black text-dark">{{ $origin }} → {{ $destination }}</h1>
                @if($tripType === 'round_trip')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-merah-100 text-merah-700 rounded-full text-xs font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        Pulang Pergi
                    </span>
                @endif
            </div>
            <p class="text-gray-warm-500">
                {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                @if($tripType === 'round_trip' && $returnDate)
                    — {{ \Carbon\Carbon::parse($returnDate)->translatedFormat('l, d F Y') }}
                @endif
            </p>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- OUTBOUND SECTION --}}
        {{-- ══════════════════════════════════════ --}}
        <div class="mb-4">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-merah-600 rounded-xl flex items-center justify-center shadow-lg shadow-merah-600/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-dark">Perjalanan Berangkat</h2>
                    <p class="text-sm text-gray-warm-500">{{ $origin }} → {{ $destination }} • {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }} • {{ $schedules->count() }} jadwal</p>
                </div>
            </div>
        </div>

        @if($schedules->isEmpty())
        <div class="card p-12 text-center mb-10">
            <div class="w-20 h-20 bg-merah-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-merah-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-dark mb-2">Tidak Ada Jadwal Berangkat</h3>
            <p class="text-gray-warm-500 mb-6">Maaf, belum ada jadwal tersedia untuk rute dan tanggal ini.</p>
            <a href="{{ route('home') }}" class="btn-primary">Cari Rute Lain</a>
        </div>
        @else
        <div class="space-y-4 mb-10">
            @foreach($schedules as $schedule)
            <div class="card-premium p-6 hover:border-merah-200 transition-all duration-300">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    {{-- Time & Route --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-6 mb-3">
                            <div class="text-center">
                                <p class="text-2xl font-black text-dark">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</p>
                                <p class="text-xs text-gray-warm-500 font-medium">{{ $origin }}</p>
                            </div>
                            <div class="flex-1 flex items-center gap-2">
                                <div class="w-2 h-2 bg-merah-500 rounded-full"></div>
                                <div class="flex-1 border-t-2 border-dashed border-merah-200"></div>
                                <span class="text-xs text-gray-warm-400 font-medium">{{ $schedule->route->formatted_duration }}</span>
                                <div class="flex-1 border-t-2 border-dashed border-merah-200"></div>
                                <div class="w-2 h-2 bg-merah-500 rounded-full"></div>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-black text-dark">{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</p>
                                <p class="text-xs text-gray-warm-500 font-medium">{{ $destination }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="badge {{ $schedule->bus->type === 'eksekutif' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($schedule->bus->type) }}
                            </span>
                            <span class="text-sm text-gray-warm-500">{{ $schedule->bus->name }}</span>
                            <span class="text-xs text-gray-warm-400">•</span>
                            <span class="text-sm text-gray-warm-500">{{ $schedule->remaining_seats }} kursi tersisa</span>
                        </div>
                    </div>

                    {{-- Price & Action --}}
                    <div class="text-right flex flex-col items-end gap-3">
                        <div>
                            <p class="text-[10px] text-gray-warm-400 uppercase tracking-widest font-bold mb-1">Harga /kursi</p>
                            @if($schedule->active_flash_sale)
                                @php
                                    $flash = $schedule->active_flash_sale;
                                    $remaining = max(0, $flash->quota - $flash->used_quota);
                                    $percent = $flash->quota > 0 ? ($flash->used_quota / $flash->quota) * 100 : 0;
                                    $isLow = $remaining <= 5;
                                @endphp
                                <div class="flex flex-col items-end">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="line-through text-gray-400 text-[10px] font-bold decoration-merah-500/40">
                                            Rp {{ number_format($schedule->price, 0, ',', '.') }}
                                        </div>
                                        <span class="bg-amber-100 text-amber-700 text-[9px] font-black px-1.5 py-0.5 rounded italic">FLASH PROMO</span>
                                    </div>
                                    <div class="text-2xl font-black text-merah-600 leading-none mb-2">
                                        Rp {{ number_format($schedule->final_price, 0, ',', '.') }}
                                    </div>
                                    
                                    {{-- Quota Progress Bar --}}
                                    <div class="w-32">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[8px] font-bold {{ $isLow ? 'text-merah-600 animate-pulse' : 'text-gray-500' }} uppercase">
                                                {{ $isLow ? 'Hampir Habis!' : $remaining.' slot lagi' }}
                                            </span>
                                            <span class="text-[8px] font-black text-gray-400">{{ round($percent) }}%</span>
                                        </div>
                                        <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-merah-500 to-amber-500 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-2xl font-black text-merah-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        @if($schedule->remaining_seats > 0)
                            <a href="{{ route('booking.select-seat', $schedule) }}" class="btn-primary btn-sm flex items-center">
                                Pilih Kursi
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <span class="badge-danger">Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ══════════════════════════════════════ --}}
        {{-- RETURN SECTION (only for round_trip) --}}
        {{-- ══════════════════════════════════════ --}}
        @if($tripType === 'round_trip' && $returnDate)
        <div class="mb-4">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-dark">Perjalanan Pulang</h2>
                    <p class="text-sm text-gray-warm-500">{{ $destination }} → {{ $origin }} • {{ \Carbon\Carbon::parse($returnDate)->translatedFormat('d M Y') }} • {{ $returnSchedules->count() }} jadwal</p>
                </div>
            </div>
        </div>

        @if($returnSchedules->isEmpty())
        <div class="card p-12 text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-dark mb-2">Tidak Ada Jadwal Pulang</h3>
            <p class="text-gray-warm-500 mb-2">Tidak ada jadwal dari {{ $destination }} ke {{ $origin }} pada tanggal {{ \Carbon\Carbon::parse($returnDate)->translatedFormat('d F Y') }}.</p>
            <p class="text-sm text-gray-warm-400">Coba pilih tanggal pulang lain atau cari rute berbeda.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($returnSchedules as $schedule)
            <div class="card-premium p-6 hover:border-blue-200 transition-all duration-300 border-l-4 border-l-blue-500">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    {{-- Time & Route --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-6 mb-3">
                            <div class="text-center">
                                <p class="text-2xl font-black text-dark">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</p>
                                <p class="text-xs text-gray-warm-500 font-medium">{{ $destination }}</p>
                            </div>
                            <div class="flex-1 flex items-center gap-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <div class="flex-1 border-t-2 border-dashed border-blue-200"></div>
                                <span class="text-xs text-gray-warm-400 font-medium">{{ $schedule->route->formatted_duration }}</span>
                                <div class="flex-1 border-t-2 border-dashed border-blue-200"></div>
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-black text-dark">{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</p>
                                <p class="text-xs text-gray-warm-500 font-medium">{{ $origin }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="badge {{ $schedule->bus->type === 'eksekutif' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($schedule->bus->type) }}
                            </span>
                            <span class="text-sm text-gray-warm-500">{{ $schedule->bus->name }}</span>
                            <span class="text-xs text-gray-warm-400">•</span>
                            <span class="text-sm text-gray-warm-500">{{ $schedule->remaining_seats }} kursi tersisa</span>
                        </div>
                    </div>

                    {{-- Price & Action --}}
                    <div class="text-right flex flex-col items-end gap-3">
                        <div>
                            <p class="text-[10px] text-gray-warm-400 uppercase tracking-widest font-bold mb-1">Harga /kursi</p>
                            @if($schedule->active_flash_sale)
                                @php
                                    $flash = $schedule->active_flash_sale;
                                    $remaining = max(0, $flash->quota - $flash->used_quota);
                                    $percent = $flash->quota > 0 ? ($flash->used_quota / $flash->quota) * 100 : 0;
                                    $isLow = $remaining <= 5;
                                @endphp
                                <div class="flex flex-col items-end">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="line-through text-gray-400 text-[10px] font-bold decoration-blue-500/40">
                                            Rp {{ number_format($schedule->price, 0, ',', '.') }}
                                        </div>
                                        <span class="bg-amber-100 text-amber-700 text-[9px] font-black px-1.5 py-0.5 rounded italic">FLASH PROMO</span>
                                    </div>
                                    <div class="text-2xl font-black text-blue-600 leading-none mb-2">
                                        Rp {{ number_format($schedule->final_price, 0, ',', '.') }}
                                    </div>
                                    <div class="w-32">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[8px] font-bold {{ $isLow ? 'text-merah-600 animate-pulse' : 'text-gray-500' }} uppercase">
                                                {{ $isLow ? 'Hampir Habis!' : $remaining.' slot lagi' }}
                                            </span>
                                            <span class="text-[8px] font-black text-gray-400">{{ round($percent) }}%</span>
                                        </div>
                                        <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-blue-500 to-amber-500 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-2xl font-black text-blue-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        @if($schedule->remaining_seats > 0)
                            <a href="{{ route('booking.select-seat', $schedule) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg text-sm hover:bg-blue-700 active:bg-blue-800 transition-all duration-200 shadow-lg shadow-blue-600/25 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0">
                                Pilih Kursi
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <span class="badge-danger">Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
