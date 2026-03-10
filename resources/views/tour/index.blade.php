@extends('layouts.app')
@section('title', 'Eksplor Nusantara - Paket Wisata Bus 88')
@section('content')
<div class="bg-slate-50 min-h-screen">
    {{-- Header Section --}}
    <div class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-dark mb-4">
                Paket <span class="text-merah-700">Wisata Indonesia</span>
            </h1>
            <p class="text-gray-500 max-w-2xl mx-auto leading-relaxed">
                Jelajahi keindahan Indonesia dengan paket wisata lengkap. Transport, hotel, makan, dan tour guide sudah termasuk!
            </p>
        </div>
    </div>

    {{-- Package List Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-0 pb-16">
        <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
            @if(isset($packages) && count($packages) > 0)
                @foreach($packages as $package)
                <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    {{-- Image with Badges --}}
                    <div class="relative h-64 md:h-72 overflow-hidden">
                        @if($package->image)
                            <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        
                        {{-- Duration Badge (Top Left) --}}
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-merah-700 text-white text-[10px] uppercase font-bold px-3 py-1.5 rounded shadow-lg">
                                {{ $package->duration_days }} Hari {{ $package->duration_days - 1 }} Malam
                            </span>
                        </div>

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-dark uppercase tracking-tight group-hover:text-merah-700 transition-colors">{{ $package->name }}</h3>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs text-gray-500">0 (0 ulasan)</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 text-xs text-gray-400 mb-4 uppercase font-medium">
                            <div class="flex items-center gap-1 group-hover:text-gray-600 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                @php
                                    $loc = explode('-', $package->name);
                                    echo trim($loc[0]);
                                @endphp
                            </div>
                            <div class="flex items-center gap-1 group-hover:text-gray-600 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $package->duration_days }}H{{ $package->duration_days - 1 }}M
                            </div>
                        </div>

                        {{-- Tags/Destinations --}}
                        <div class="flex flex-wrap gap-2 mb-8">
                            @foreach($package->destinations ?? [] as $dest)
                            <span class="px-3 py-1 bg-gray-50 text-gray-600 text-[10px] font-bold rounded-lg border border-gray-100 group-hover:border-merah-200 group-hover:bg-merah-50 transition-all">
                                {{ $dest }}
                            </span>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                            <div class="text-xl font-black text-merah-700 group-hover:scale-110 transition-transform origin-left">
                                Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('tour.show', $package->slug) }}" class="bg-merah-600 hover:bg-merah-700 text-white px-8 py-2.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-merah-100 group-hover:px-10">
                                Booking
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="col-span-full py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-dark mb-2">Belum Tersedia</h3>
                <p class="text-gray-400">Paket wisata sedang dikurasi oleh tim kami. Kembali lagi nanti ya!</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
