@extends('layouts.app')

@section('title', 'Bus 88 - Tiket Bus & Sewa Bus Terpercaya')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Page-specific: Swiper promo & aspect ratio */
    .promo-swiper { padding-bottom: 50px !important; }
    .aspect-promo { aspect-ratio: 16/9; }
    @media (min-width: 768px) { .aspect-promo { aspect-ratio: 4/1; } }
    .promo-swiper .swiper-pagination-bullet { background: #cc0000; opacity: 0.2; }
    .promo-swiper .swiper-pagination-bullet-active { opacity: 1; width: 20px; border-radius: 4px; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden hero-section" style="min-height: 90vh;">
    {{-- Background: foto bus --}}
    <div class="absolute inset-0 overflow-hidden">
        <img src="{{ asset('images/bg.png') }}"
             alt="Bus IND'S 88 Trans"
             class="w-full h-full object-cover object-center hero-img">
        {{-- Overlay gelap supaya teks tetap terbaca --}}
        <div class="absolute inset-0" style="background: linear-gradient(105deg, rgba(10,0,0,0.82) 0%, rgba(140,0,0,0.65) 50%, rgba(10,0,0,0.45) 100%);"></div>
    </div>
    {{-- Decorative Elements + 3D Shapes --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 left-10 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/3 rounded-full blur-3xl"></div>
        {{-- Pattern overlay --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 50px 50px;"></div>

        {{-- 3D Floating Shapes --}}
        <div class="hidden lg:block absolute top-[15%] right-[8%] shape-3d-ring opacity-40"></div>
        <div class="hidden lg:block absolute bottom-[20%] left-[5%] shape-3d-cube opacity-50" style="animation-delay: 2s;"></div>
        <div class="hidden lg:block absolute top-[30%] left-[15%] w-3 h-3 bg-white/20 rounded-full animate-orbit" style="animation-duration: 18s;"></div>
        <div class="hidden lg:block absolute bottom-[35%] right-[20%] w-2 h-2 bg-merah-400/30 rounded-full animate-orbit" style="animation-duration: 14s; animation-delay: 3s;"></div>
        <div class="hidden lg:block absolute top-[60%] right-[12%] w-6 h-6 border border-white/15 rounded-lg animate-float-3d" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Left: Text --}}
            <div class="text-white animate-slide-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium">Tersedia untuk booking online</span>
                </div>
                <h1 class="text-4xl lg:text-6xl font-black leading-tight mb-6">
                    Perjalanan <br>
                    <span class="text-white/90">Aman & Nyaman</span> <br>
                    Bersama <span class="underline decoration-4 decoration-white/30 underline-offset-4">Bus 88</span>
                </h1>
                <p class="text-lg text-white/80 leading-relaxed mb-8 max-w-lg">
                    Layanan tiket bus antar kota dan sewa bus charter terpercaya. Harga transparan, pembayaran mudah via Midtrans.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#search" class="btn-white btn-hero-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari Tiket
                    </a>
                    <a href="{{ route('rental.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white/10 text-white font-semibold rounded-xl border-2 border-white/20 backdrop-blur-sm btn-hero-ghost">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        Sewa Bus
                    </a>
                </div>
            </div>

            {{-- Right: Search Card --}}
            <div id="search" class="animate-slide-up" style="animation-delay: 0.2s;">
                <div class="glass-card p-8 bg-white/95 backdrop-blur-xl search-card" x-data="{ tripType: 'one_way' }">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-dark">Cari Tiket Bus</h3>
                                <p class="text-sm text-gray-warm-500">Temukan jadwal & harga terbaik</p>
                            </div>
                        </div>
                    </div>

                    {{-- Trip Type Toggle --}}
                    <div class="flex items-center bg-gray-warm-50 rounded-xl p-1 mb-6">
                        <button type="button" @click="tripType = 'one_way'"
                                :class="tripType === 'one_way' ? 'bg-white text-merah-600 shadow-sm' : 'text-gray-warm-500 hover:text-gray-warm-700'"
                                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm font-semibold transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            Sekali Jalan
                        </button>
                        <button type="button" @click="tripType = 'round_trip'"
                                :class="tripType === 'round_trip' ? 'bg-white text-merah-600 shadow-sm' : 'text-gray-warm-500 hover:text-gray-warm-700'"
                                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm font-semibold transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            Pulang Pergi
                        </button>
                    </div>

                    <form action="{{ route('schedules.search') }}" method="GET" class="space-y-4">
                        <input type="hidden" name="trip_type" :value="tripType">
                        <div>
                            <label class="label-field">Kota Asal</label>
                            <select name="origin" class="select-field" required>
                                <option value="">Pilih kota asal</option>
                                @foreach($origins as $origin)
                                    <option value="{{ $origin }}">{{ $origin }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label-field">Kota Tujuan</label>
                            <select name="destination" class="select-field" required>
                                <option value="">Pilih kota tujuan</option>
                                @foreach($destinations as $dest)
                                    <option value="{{ $dest }}">{{ $dest }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid gap-4" :class="tripType === 'round_trip' ? 'grid-cols-2' : 'grid-cols-1'">
                            <div>
                                <label class="label-field">Tanggal Berangkat</label>
                                <input type="date" name="date" id="departure_date" class="input-field" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                                       x-on:change="if(tripType === 'round_trip') { $refs.returnDate.min = $event.target.value; if($refs.returnDate.value < $event.target.value) $refs.returnDate.value = $event.target.value; }">
                            </div>
                            <div x-show="tripType === 'round_trip'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 -translate-y-2" x-cloak>
                                <label class="label-field">Tanggal Pulang</label>
                                <input type="date" name="return_date" x-ref="returnDate" class="input-field" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" :required="tripType === 'round_trip'">
                            </div>
                        </div>
                        <button type="submit" class="btn-primary w-full text-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Cari Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features Section (3D Tilt Cards) --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 reveal-3d">
            <h2 class="text-3xl lg:text-4xl font-black text-dark mb-4">Mengapa <span class="text-gradient-merah">Bus 88</span>?</h2>
            <p class="text-gray-warm-500 text-lg max-w-2xl mx-auto">Kami berkomitmen memberikan layanan transportasi terbaik untuk perjalanan Anda</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 perspective-container">
            {{-- Feature 1 — 3D Tilt --}}
            <div class="tilt-card reveal-3d" data-tilt>
                <div class="tilt-card-inner card-premium p-8 text-center group feature-card tilt-shadow relative overflow-hidden">
                    <div class="tilt-shine"></div>
                    <div class="w-16 h-16 bg-merah-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-merah-600 transition-colors duration-300 feature-icon">
                        <svg class="w-8 h-8 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3 group-hover:text-merah-700 transition-colors duration-300">Aman & Terpercaya</h3>
                    <p class="text-gray-warm-500 leading-relaxed">Armada terawat dengan pengemudi profesional berpengalaman untuk keselamatan Anda</p>
                </div>
            </div>
            {{-- Feature 2 — 3D Tilt --}}
            <div class="tilt-card reveal-3d" data-tilt style="transition-delay: 0.15s;">
                <div class="tilt-card-inner card-premium p-8 text-center group feature-card tilt-shadow relative overflow-hidden">
                    <div class="tilt-shine"></div>
                    <div class="w-16 h-16 bg-merah-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-merah-600 transition-colors duration-300 feature-icon">
                        <svg class="w-8 h-8 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3 group-hover:text-merah-700 transition-colors duration-300">Harga Transparan</h3>
                    <p class="text-gray-warm-500 leading-relaxed">Harga jelas tanpa biaya tersembunyi. Pembayaran mudah via Midtrans</p>
                </div>
            </div>
            {{-- Feature 3 — 3D Tilt --}}
            <div class="tilt-card reveal-3d" data-tilt style="transition-delay: 0.3s;">
                <div class="tilt-card-inner card-premium p-8 text-center group feature-card tilt-shadow relative overflow-hidden">
                    <div class="tilt-shine"></div>
                    <div class="w-16 h-16 bg-merah-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-merah-600 transition-colors duration-300 feature-icon">
                        <svg class="w-8 h-8 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3 group-hover:text-merah-700 transition-colors duration-300">Booking Cepat</h3>
                    <p class="text-gray-warm-500 leading-relaxed">Pesan tiket dalam hitungan menit. Pilih kursi favorit dan bayar langsung</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Promo section removed by user request --}}



{{-- Popular Routes --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-5xl font-black text-dark mb-4">Temukan Rute <span class="text-gradient-merah">Terbaik</span></h2>
            <p class="text-gray-warm-500 text-lg">Perjalanan nyaman, aman, dan terjangkau ke berbagai destinasi pilihan</p>
        </div>

        {{-- Benefits Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-warm-50 border border-gray-warm-100">
                <div class="w-10 h-10 bg-merah-50 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-dark uppercase tracking-wider">Harga Terbaik</h4>
                    <p class="text-[10px] text-gray-warm-500">Jaminan harga termurah</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-warm-50 border border-gray-warm-100">
                <div class="w-10 h-10 bg-merah-50 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-dark uppercase tracking-wider">Aman & Nyaman</h4>
                    <p class="text-[10px] text-gray-warm-500">Perjalanan aman dan nyaman</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-warm-50 border border-gray-warm-100">
                <div class="w-10 h-10 bg-merah-50 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-dark uppercase tracking-wider">Berangkat Tiap Hari</h4>
                    <p class="text-[10px] text-gray-warm-500">Banyak pilihan jadwal</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-warm-50 border border-gray-warm-100">
                <div class="w-10 h-10 bg-merah-50 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-dark uppercase tracking-wider">Customer Support</h4>
                    <p class="text-[10px] text-gray-warm-500">Siap membantu 24/7</p>
                </div>
            </div>
        </div>

        {{-- Route Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            @forelse($popularRoutes->take(4) as $pr)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 group flex flex-col h-full border border-gray-warm-100">
                {{-- Image Header --}}
                <div class="relative h-48 md:h-52 overflow-hidden">
                    <img src="{{ asset('storage/popular_routes/' . $pr->image) }}" 
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                         alt="{{ $pr->route->origin }}">
                    
                    @if($pr->badge_text)
                    <div class="absolute top-4 right-4">
                        <span class="bg-merah-600 text-white text-[8px] font-black px-2.5 py-1 rounded-lg shadow-lg uppercase tracking-wider">
                            {{ $pr->badge_text }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-extrabold text-dark line-clamp-1">
                            {{ $pr->route->origin }} — {{ $pr->route->destination }}
                        </h3>
                        <span class="text-sm font-black text-merah-600 whitespace-nowrap ml-2">
                            {{ $pr->price_display ?? 'IDR ' . number_format($pr->route->base_price / 1000, 0) . 'K' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-1.5 text-gray-warm-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-[10px] font-bold">{{ $pr->duration_display ?? $pr->route->formatted_duration }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-warm-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span class="text-[10px] font-bold">{{ $pr->class_display ?? 'Eksekutif' }}</span>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <a href="{{ route('schedules.search') }}?origin={{ $pr->route->origin }}&destination={{ $pr->route->destination }}&date={{ date('Y-m-d') }}" 
                           class="flex items-center justify-between w-full py-3 px-5 bg-merah-600 text-white font-black text-xs rounded-xl hover:bg-merah-700 transition-all duration-300 group/btn">
                            Pilih Rute
                            <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
                {{-- Fallback --}}
                @foreach($routes->take(4) as $route)
                    <div class="bg-white rounded-3xl p-5 border border-gray-warm-100 flex flex-col">
                        <p class="font-bold text-sm mb-4">{{ $route->origin }} - {{ $route->destination }}</p>
                        <a href="{{ route('schedules.search') }}?origin={{ $route->origin }}&destination={{ $route->destination }}&date={{ date('Y-m-d') }}" class="mt-auto py-2 text-center bg-merah-600 text-white rounded-xl text-xs font-bold">Cari Jadwal</a>
                    </div>
                @endforeach
            @endforelse
        </div>

        {{-- Bottom Banner --}}
        <div class="bg-merah-50 rounded-[2rem] p-4 md:p-6 flex flex-col md:flex-row items-center justify-between gap-4 border border-merah-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-merah-600 shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-black text-dark">Jadwal Fleksibel</h4>
                    <p class="text-[10px] text-gray-warm-500">Pilih jadwal keberangkatan yang sesuai dengan rencana perjalanan Anda.</p>
                </div>
            </div>
            <a href="#search" class="px-6 py-3 bg-white border border-merah-100 text-merah-600 text-xs font-black rounded-xl hover:bg-merah-600 hover:text-white transition-all flex items-center gap-2" onclick="document.getElementById('search').scrollIntoView({behavior: 'smooth'}); return false;">
                Lihat Semua Jadwal
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Banner Promo Section --}}
@if($promoBanners->isNotEmpty())
<section id="promo-section" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black text-dark">Promo <span class="text-gradient-merah">Terbatas</span></h2>
                <p class="text-gray-warm-500">Jangan lewatkan penawaran menarik hari ini</p>
            </div>
        </div>

        <div class="swiper promo-swiper rounded-[2rem] overflow-hidden shadow-2xl shadow-blue-900/10">
            <div class="swiper-wrapper">
                @foreach($promoBanners as $banner)
                @php 
                    if ($banner->link && $banner->link !== '#') {
                        $promoUrl = $banner->link;
                    } else {
                        switch ($banner->target_type) {
                            case 'rental':
                                $promoUrl = route('rental.index') . '?promo=' . $banner->promo_code;
                                break;
                            case 'tour':
                                $promoUrl = route('tour.index') . '?promo=' . $banner->promo_code;
                                break;
                            case 'ticket':
                            default:
                                $promoUrl = '#search';
                                break;
                        }
                    }
                    $isExternal = Str::startsWith($promoUrl, 'http') && !Str::contains($promoUrl, request()->getHost());
                    $target = $isExternal ? '_blank' : '_self';
                @endphp
                <div class="swiper-slide relative group cursor-pointer aspect-promo"
                     onclick="if('{{ $promoUrl }}' === '#search') { document.getElementById('search').scrollIntoView({behavior: 'smooth'}); } else { window.open('{{ $promoUrl }}', '{{ $target }}'); }">
                    {{-- Background Image --}}
                    <img src="{{ $banner->image_url }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[5000ms] group-hover:scale-110" alt="{{ $banner->title }}" loading="lazy">
                    
                    {{-- Overlay Gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-dark/90 via-dark/40 to-transparent"></div>

                    {{-- Content --}}
                    <div class="absolute inset-0 flex items-center px-8 md:px-16">
                        <div class="max-w-xl text-white">
                            <h3 class="text-2xl md:text-4xl lg:text-5xl font-black mb-4 leading-tight transform translate-y-4 opacity-0 transition-all duration-700 delay-100 group-[.swiper-slide-active]:translate-y-0 group-[.swiper-slide-active]:opacity-100">
                                {{ $banner->title }}
                            </h3>
                            
                            @if($banner->description)
                            <p class="text-sm md:text-lg text-white/80 mb-6 line-clamp-2 transform translate-y-4 opacity-0 transition-all duration-700 delay-200 group-[.swiper-slide-active]:translate-y-0 group-[.swiper-slide-active]:opacity-100">
                                {{ $banner->description }}
                            </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-4 transform translate-y-4 opacity-0 transition-all duration-700 delay-300 group-[.swiper-slide-active]:translate-y-0 group-[.swiper-slide-active]:opacity-100">
                                {{-- Promo Code Box --}}
                                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-3 flex items-center gap-4" onclick="event.stopPropagation()">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] uppercase font-bold text-white/60 tracking-widest">Kode Promo</span>
                                        <span class="text-lg font-black tracking-tighter">{{ $banner->promo_code }}</span>
                                    </div>
                                    <button onclick="copyPromoCode('{{ $banner->promo_code }}')" class="bg-white text-dark hover:bg-merah-600 hover:text-white transition-all px-4 py-2 rounded-lg text-xs font-black uppercase">
                                        Salin
                                    </button>
                                </div>

                                {{-- Countdown if needed --}}
                                @if(now()->diffInDays($banner->end_date) <= 7)
                                <div class="bg-amber-500/90 backdrop-blur-md text-white px-4 py-3 rounded-xl flex flex-col" onclick="event.stopPropagation()">
                                    <span class="text-[10px] uppercase font-bold text-white/80 tracking-widest">Berakhir Dalam</span>
                                    <span class="text-sm font-black whitespace-nowrap countdown-timer" data-until="{{ $banner->end_date->endOfDay()->toIso8601String() }}">
                                        Menghitung...
                                    </span>
                                </div>
                                @else
                                <div class="bg-blue-600/80 backdrop-blur-md text-white px-4 py-3 rounded-xl flex flex-col" onclick="event.stopPropagation()">
                                    <span class="text-[10px] uppercase font-bold text-white/80 tracking-widest">Berlaku Hingga</span>
                                    <span class="text-sm font-black whitespace-nowrap">{{ $banner->end_date->translatedFormat('d F Y') }}</span>
                                </div>
                                @endif

                                <a href="{{ $promoUrl }}" target="{{ $target }}" onclick="event.stopPropagation(); if('{{ $promoUrl }}' === '#search') { document.getElementById('search').scrollIntoView({behavior: 'smooth'}); return false; }" class="btn-primary py-4 px-8 shadow-lg shadow-merah-600/20">Gunakan Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- Pagination --}}
            <div class="swiper-pagination !bottom-8 !left-16 !w-auto !justify-start"></div>

            {{-- Navigation Buttons --}}
            <button class="promo-prev absolute top-1/2 left-4 -translate-y-1/2 z-10 w-12 h-12 bg-white/30 hover:bg-white text-white hover:text-red-600 rounded-full flex items-center justify-center backdrop-blur-md transition-all border border-white/40 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button class="promo-next absolute top-1/2 right-4 -translate-y-1/2 z-10 w-12 h-12 bg-white/30 hover:bg-white text-white hover:text-red-600 rounded-full flex items-center justify-center backdrop-blur-md transition-all border border-white/40 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
</section>
@endif



{{-- Reviews Section --}}
@if($reviews->isNotEmpty())
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div class="max-w-2xl">
                <h2 class="text-3xl lg:text-5xl font-black text-dark mb-4">Kata <span class="text-gradient-merah">Pelanggan</span> Kami</h2>
                <p class="text-gray-warm-500 text-lg">Cerita asli dari mereka yang telah melakukan perjalanan bersama Bus 88</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex -space-x-3">
                    @foreach($reviews->take(4) as $rev)
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($rev->user->name) }}&background=cc0000&color=fff" class="w-10 h-10 rounded-full border-4 border-white object-cover shadow-sm" alt="">
                    @endforeach
                </div>
                <p class="text-sm font-bold text-dark ml-2">4.9/5 Rating</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($reviews as $review)
            <div class="bg-white rounded-3xl p-8 review-card relative flex flex-col h-full shadow-sm hover:shadow-xl transition-all duration-300 border-gray-100">
                <div class="flex items-center gap-1 mb-4">
                    @for($i=1; $i<=5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>

                <p class="text-gray-700 leading-relaxed mb-6 italic flex-grow">"{{ Str::limit($review->comment, 150) }}"</p>

                @if($review->image)
                <div class="mb-6 rounded-2xl overflow-hidden h-40 bg-gray-100">
                    <img src="{{ asset('storage/' . $review->image) }}" class="w-full h-full object-cover review-img" alt="Foto ulasan">
                </div>
                @endif

                <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
                    <div class="w-12 h-12 bg-merah-50 rounded-full flex items-center justify-center font-bold text-merah-600 shadow-inner flex-shrink-0 relative">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-bold text-dark text-sm leading-tight">{{ $review->user->name }}</p>
                        <p class="text-xs text-gray-warm-400 mt-1">Pelanggan Terverifikasi</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-20 gradient-merah-dark relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 30px 30px;"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center text-white">
        <h2 class="text-3xl lg:text-5xl font-black mb-6">Butuh Sewa Bus untuk Rombongan?</h2>
        <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">Sewa bus untuk wisata, acara kantor, atau kebutuhan khusus lainnya. Armada bersih dan lengkap dengan fasilitas premium.</p>
        <a href="{{ route('rental.index') }}" class="btn-white text-lg px-8 py-4 btn-cta">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Ajukan Sewa Bus
        </a>
    </div>
</section>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Promo Swiper ──
        const promoSwiper = new Swiper('.promo-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.promo-next',
                prevEl: '.promo-prev',
            },
            effect: 'slide',
            speed: 800,
        });

        // ── Copy Promo Code ──
        window.copyPromoCode = function(code) {
            navigator.clipboard.writeText(code).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `Kode [${code}] berhasil disalin!`,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        };

        // ── Countdown Timer ──
        function updateCountdowns() {
            const timers = document.querySelectorAll('.countdown-timer');
            timers.forEach(timer => {
                const until = new Date(timer.dataset.until).getTime();
                const now = new Date().getTime();
                const distance = until - now;

                if (distance < 0) {
                    timer.innerHTML = "PROMO BERAKHIR";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timer.innerHTML = `${days}h ${hours}j ${minutes}m ${seconds}d`;
            });
        }
        setInterval(updateCountdowns, 1000);
        updateCountdowns();

        // ── 3D Tilt Card Engine ──
        document.querySelectorAll('[data-tilt]').forEach(card => {
            const inner = card.querySelector('.tilt-card-inner');
            if (!inner) return;

            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -8;
                const rotateY = ((x - centerX) / centerX) * 8;

                inner.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            });

            card.addEventListener('mouseleave', () => {
                inner.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            });
        });

        // ── Scroll Reveal 3D (IntersectionObserver) ──
        const revealElements = document.querySelectorAll('.reveal-3d');
        if (revealElements.length > 0) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

            revealElements.forEach(el => observer.observe(el));
        }
    });
</script>
@endpush
@endsection
