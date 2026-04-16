@extends('layouts.app')

@section('title', 'Bus 88 - Tiket Bus & Sewa Bus Terpercaya')

@push('styles')
<style>
    /* ── Hero foto zoom on hover ── */
    .hero-img {
        transition: transform 8s ease;
        transform: scale(1.05);
    }
    .hero-section:hover .hero-img {
        transform: scale(1.12);
    }

    /* ── Feature card hover ── */
    .feature-card {
        transition: transform 0.35s cubic-bezier(.22,.68,0,1.2), box-shadow 0.35s ease;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 48px rgba(204,0,0,0.12), 0 8px 20px rgba(0,0,0,0.08);
    }
    .feature-card:hover .feature-icon {
        transform: scale(1.15) rotate(-5deg);
    }
    .feature-icon {
        transition: transform 0.35s cubic-bezier(.22,.68,0,1.2);
    }

    /* ── Route card hover ── */
    .route-card {
        transition: transform 0.3s cubic-bezier(.22,.68,0,1.2), box-shadow 0.3s ease, border-color 0.3s ease;
        border: 1.5px solid transparent;
    }
    .route-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(204,0,0,0.13), 0 6px 16px rgba(0,0,0,0.07);
        border-color: rgba(204,0,0,0.18);
    }
    .route-card:hover .route-price {
        color: #b80000;
        transform: scale(1.05);
    }
    .route-card:hover .route-arrow {
        transform: translateX(4px);
    }
    .route-price  { transition: color 0.25s ease, transform 0.25s ease; display: inline-block; }
    .route-arrow  { transition: transform 0.25s ease; display: inline-block; }

    /* ── Search card hover ── */
    .search-card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .search-card:hover {
        box-shadow: 0 32px 64px rgba(0,0,0,0.18);
        transform: translateY(-4px);
    }

    /* ── CTA button hover ── */
    .btn-cta {
        transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
    }
    .btn-cta:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 16px 40px rgba(255,255,255,0.25);
    }
    .btn-cta:active {
        transform: translateY(0) scale(0.99);
    }

    /* ── Hero CTA buttons ── */
    .btn-hero-white {
        transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
    }
    .btn-hero-white:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(255,255,255,0.3);
    }
    .btn-hero-ghost {
        transition: transform 0.25s ease, background 0.25s ease, border-color 0.25s ease;
    }
    .btn-hero-ghost:hover {
        transform: translateY(-3px);
        background: rgba(255,255,255,0.18);
        border-color: rgba(255,255,255,0.5);
    }

    /* ── Scrollbar hide ── */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* ── Review card styles ── */
    .review-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px -8px rgba(0,0,0,0.1);
        border-color: #e2e8f0;
    }
    .review-img {
        transition: transform 0.5s ease;
    }
    .review-card:hover .review-img {
        transform: scale(1.05);
    }
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
    {{-- Decorative Elements --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 left-10 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/3 rounded-full blur-3xl"></div>
        {{-- Pattern overlay --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 50px 50px;"></div>
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

{{-- Features Section --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl lg:text-4xl font-black text-dark mb-4">Mengapa <span class="text-gradient-merah">Bus 88</span>?</h2>
            <p class="text-gray-warm-500 text-lg max-w-2xl mx-auto">Kami berkomitmen memberikan layanan transportasi terbaik untuk perjalanan Anda</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            {{-- Feature 1 --}}
            <div class="card-premium p-8 text-center group feature-card">
                <div class="w-16 h-16 bg-merah-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-merah-600 transition-colors duration-300 feature-icon">
                    <svg class="w-8 h-8 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3 group-hover:text-merah-700 transition-colors duration-300">Aman & Terpercaya</h3>
                <p class="text-gray-warm-500 leading-relaxed">Armada terawat dengan pengemudi profesional berpengalaman untuk keselamatan Anda</p>
            </div>
            {{-- Feature 2 --}}
            <div class="card-premium p-8 text-center group feature-card">
                <div class="w-16 h-16 bg-merah-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-merah-600 transition-colors duration-300 feature-icon">
                    <svg class="w-8 h-8 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3 group-hover:text-merah-700 transition-colors duration-300">Harga Transparan</h3>
                <p class="text-gray-warm-500 leading-relaxed">Harga jelas tanpa biaya tersembunyi. Pembayaran mudah via Midtrans</p>
            </div>
            {{-- Feature 3 --}}
            <div class="card-premium p-8 text-center group feature-card">
                <div class="w-16 h-16 bg-merah-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-merah-600 transition-colors duration-300 feature-icon">
                    <svg class="w-8 h-8 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3 group-hover:text-merah-700 transition-colors duration-300">Booking Cepat</h3>
                <p class="text-gray-warm-500 leading-relaxed">Pesan tiket dalam hitungan menit. Pilih kursi favorit dan bayar langsung</p>
            </div>
        </div>
    </div>
</section>

{{-- Promo section removed by user request --}}



{{-- Popular Routes --}}
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl lg:text-4xl font-black text-dark mb-4">Rute <span class="text-gradient-merah">Populer</span></h2>
            <p class="text-gray-warm-500 text-lg">Rute favorit pelanggan kami</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($routes->take(8) as $route)
            <div class="bg-white rounded-2xl p-6 group cursor-pointer route-card"
                 onclick="window.location='{{ route('schedules.search') }}?origin={{ $route->origin }}&destination={{ $route->destination }}&date={{ date('Y-m-d') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center group-hover:bg-merah-600 transition-colors duration-300">
                        <svg class="w-5 h-5 text-merah-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-warm-400 bg-gray-warm-50 px-2.5 py-1 rounded-full">{{ $route->formatted_duration }}</span>
                </div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-sm font-bold text-dark">{{ $route->origin }}</span>
                    <svg class="w-4 h-4 text-merah-400 route-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    <span class="text-sm font-bold text-dark">{{ $route->destination }}</span>
                </div>
                <p class="text-xs text-gray-warm-500 mb-3">{{ $route->distance ? $route->distance . ' km' : '' }}</p>
                <div class="flex items-center justify-between pt-3 border-t border-gray-warm-100">
                    <span class="text-lg font-black text-merah-600 route-price">Rp {{ number_format($route->base_price, 0, ',', '.') }}</span>
                    <span class="text-xs text-gray-warm-500">mulai dari</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

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
@endpush
@endsection
