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
            <h1 class="text-3xl font-black text-dark mb-2">{{ $origin }} → {{ $destination }}</h1>
            <p class="text-gray-warm-500">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }} • {{ $schedules->count() }} jadwal tersedia</p>
        </div>

        @if($schedules->isEmpty())
        <div class="card p-12 text-center">
            <div class="w-20 h-20 bg-merah-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-merah-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-dark mb-2">Tidak Ada Jadwal</h3>
            <p class="text-gray-warm-500 mb-6">Maaf, belum ada jadwal tersedia untuk rute dan tanggal ini.</p>
            <a href="{{ route('home') }}" class="btn-primary">Cari Rute Lain</a>
        </div>
        @else
        <div class="space-y-4">
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
                            <p class="text-xs text-gray-warm-400 uppercase tracking-wider font-semibold">Harga /kursi</p>
                            <p class="text-2xl font-black text-merah-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</p>
                        </div>
                        @if($schedule->remaining_seats > 0)
                            <a href="{{ route('booking.select-seat', $schedule) }}" class="btn-primary btn-sm">
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
    </div>
</div>
@endsection
