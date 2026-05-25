@extends('layouts.app')
@section('title', 'Sewa Bus Premium - Bus 88')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
/* ── Leaflet Tailwind Fix ── */
.leaflet-container img.leaflet-tile { max-width: none !important; max-height: none !important; }
.leaflet-container img { max-width: none !important; }

.rental-hero {
    padding-top: 7rem;
    padding-bottom: 10rem;
    background: #000;
    position: relative;
    overflow: hidden;
}

.hero-bg-media {
    position: absolute;
    inset: 0;
    z-index: 0;
    background: url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&q=80&w=1600') center/cover;
    opacity: 0.4;
    pointer-events: none;
}



.stat-chip {
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 1rem;
    padding: 0.75rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    animation: float 3s ease-in-out infinite;
}

.stat-chip:nth-child(2) { animation-delay: 0.5s; }
.stat-chip:nth-child(3) { animation-delay: 1s; }

#rental-map {
    height: 400px;
    width: 100%;
    border-radius: 1.5rem;
    z-index: 1;
    border: 2px solid #e5e7eb;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
}

.form-card {
    background: #fff;
    border-radius: 1.5rem;
    box-shadow: 0 20px 60px -15px rgba(0,0,0,.1);
    border: 1px solid rgba(0,0,0,.03);
    padding: 2.5rem;
    transition: all 0.3s ease;
}

.form-card:hover {
    box-shadow: 0 25px 70px -15px rgba(0,0,0,.15);
}

.side-card {
    border-radius: 1.5rem;
    box-shadow: 0 10px 30px -5px rgba(0,0,0,.08);
    border: 1px solid rgba(0,0,0,.04);
    padding: 2rem;
    transition: all 0.3s ease;
}

.side-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 40px -10px rgba(0,0,0,.12);
}

select.input-field {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 1.25rem center;
    background-repeat: no-repeat;
    background-size: 1.2em;
    padding-right: 3rem;
}

.input-field {
    transition: all 0.2s ease;
}

.input-field:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
}

/* Hide Spin Buttons */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }

/* Timeline Style for Tutorial */
.step-item {
    position: relative;
    padding-left: 2.5rem;
}

.step-item::before {
    content: '';
    position: absolute;
    left: 0.9rem;
    top: 2.2rem;
    bottom: -1.2rem;
    width: 2px;
    background: linear-gradient(180deg, rgba(220, 38, 38, 0.3) 0%, rgba(220, 38, 38, 0.05) 100%);
}

.step-item:last-child::before {
    display: none;
}

.step-number {
    position: absolute;
    left: 0;
    top: 0;
    width: 2rem;
    height: 2rem;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    border-radius: 0.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.85rem;
    box-shadow: 0 3px 10px rgba(220, 38, 38, 0.25);
    z-index: 2;
}

.success-card {
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-badge {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen font-inter">

    {{-- Hero --}}
    <section class="rental-hero relative">
        {{-- Ken Burns Background Media --}}
        <div class="hero-bg-media"></div>



        {{-- Dynamic Light Streaks / Speed Trails --}}
        <div class="light-streak light-streak-1"></div>
        <div class="light-streak light-streak-2"></div>
        <div class="light-streak light-streak-3"></div>

        {{-- Red glow behind bus --}}
        <div class="hero-glow absolute right-[10%] top-[20%] z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[480px]">

                {{-- Left: Text --}}
                <div class="text-left">
                    <span class="hero-badge inline-flex items-center gap-2 px-4 py-2 rounded-full text-white text-[11px] font-bold uppercase tracking-widest mb-8 border border-white/15 bg-white/8 backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse"></span>
                        Premium Charter Service
                    </span>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                        Sewa Bus <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-red-600">Premium</span>
                    </h1>

                    <p class="text-gray-300 text-lg md:text-xl leading-relaxed mb-8 max-w-xl">
                        Nikmati perjalanan yang aman dan nyaman dengan armada eksklusif kami. Fasilitas lengkap dan pilihan kapasitas yang beragam untuk setiap kebutuhan rombongan Anda.
                    </p>

                    {{-- Feature Chips --}}
                    <div class="flex flex-wrap gap-3">
                        <div class="stat-chip">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-white text-xs font-semibold">Armada Terawat</span>
                        </div>
                        <div class="stat-chip">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                            <span class="text-white text-xs font-semibold">Respon Cepat</span>
                        </div>
                        <div class="stat-chip">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-white text-xs font-semibold">Harga Bersaing</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-28 md:-mt-36 pb-32 md:pb-48 relative z-10">
        <div class="grid lg:grid-cols-12 gap-8 items-start">

            {{-- ── FORM ── --}}
            <div class="lg:col-span-8">
                @if(session('rental_success'))
                <div class="form-card success-card text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-3">Pengajuan Berhasil!</h2>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Tim operasional kami akan segera menghubungi Anda melalui WhatsApp.</p>
                    
                    <div class="max-w-md mx-auto bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 text-left space-y-3 mb-8 border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-400 font-black uppercase text-[10px] tracking-widest">KODE BOOKING</span>
                            <span class="font-black text-xl text-red-600">{{ session('rental_success')['code'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-400 font-bold text-xs">Tujuan</span>
                            <span class="font-bold text-gray-900">{{ session('rental_success')['destination'] }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-gray-400 font-bold text-xs">Tanggal</span>
                            <span class="font-bold text-gray-900">{{ session('rental_success')['start_date'] }}</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('dashboard') }}" class="btn-primary px-8 py-3.5 font-bold rounded-xl">Dashboard Saya</a>
                        <a href="{{ route('rental.index') }}" class="btn-secondary px-8 py-3.5 font-bold text-gray-600 rounded-xl">Sewa Lagi</a>
                    </div>
                </div>
                @else
                <div class="form-card relative overflow-hidden">
                    {{-- Subtle Deco --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-red-50 to-transparent rounded-full -mr-32 -mt-32 opacity-50 pointer-events-none"></div>

                    <form action="{{ route('rental.store') }}" method="POST" class="space-y-8 relative z-10">
                        @csrf

                        <div class="flex items-center gap-3">
                            <div class="w-1 h-10 bg-gradient-to-b from-red-600 to-red-400 rounded-full"></div>
                            <h2 class="text-2xl md:text-3xl font-black text-gray-900">Formulir Sewa Bus</h2>
                        </div>

                        {{-- Bus Selection --}}
                        <div class="space-y-2.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Armada Bus</label>
                            <select name="bus_id" id="bus_selector" class="input-field py-3.5 font-semibold text-gray-800 cursor-pointer w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100" required>
                                <option value="">— Silahkan Pilih Armada —</option>
                                @foreach($buses as $bus)
                                <option value="{{ $bus->id }}"
                                    data-capacity="{{ $bus->capacity }}"
                                    {{ old('bus_id')==$bus->id?'selected':'' }}>
                                    {{ $bus->name }} ({{ $bus->type }} - {{ $bus->capacity }} Kursi)
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dates --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2.5">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="input-field py-3.5 w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 font-semibold" min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}" required>
                            </div>
                            <div class="space-y-2.5">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="input-field py-3.5 w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 font-semibold" min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}" required>
                            </div>
                        </div>

                        {{-- Pickup Location --}}
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Lokasi Penjemputan</label>
                            <div class="relative">
                                <input type="text" id="pickup_location" name="pickup_location"
                                    class="input-field py-3.5 pr-14 w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 font-semibold"
                                    placeholder="Ketik alamat lengkap atau pilih di peta..."
                                    value="{{ old('pickup_location') }}" pattern=".*\S+.*" title="Lokasi penjemputan tidak boleh hanya spasi kosong" required>
                                <button type="button" id="btn-locate"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                            
                            {{-- MAP --}}
                            <div id="rental-map" class="mt-3"></div>
                            <p class="text-[10px] text-gray-400 flex items-center gap-2 mt-2">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Klik pada peta untuk menandai titik jemput yang akurat
                            </p>
                        </div>

                        {{-- Destination + Passengers --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2.5">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kota Tujuan</label>
                                <input type="text" name="destination" class="input-field py-3.5 w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 font-semibold" placeholder="Contoh: Yogyakarta, Bali..." value="{{ old('destination') }}" pattern=".*\S+.*" title="Tidak boleh hanya spasi kosong" required>
                            </div>
                            <div class="space-y-2.5">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jumlah Penumpang</label>
                                <input type="number" name="passenger_count" id="passenger_count" class="input-field py-3.5 w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 font-semibold" placeholder="Minimal 1 orang" min="1" onkeydown="if(['+', '-', 'e', 'E', '.'].includes(event.key)) event.preventDefault();" value="{{ old('passenger_count') }}" required>
                                <p id="capacity_warning" class="text-[10px] font-bold text-red-500 hidden">⚠️ Jumlah penumpang melebihi kapasitas bus!</p>
                            </div>
                        </div>

                        {{-- Purpose --}}
                        <div class="space-y-2.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Keperluan Sewa</label>
                            <textarea name="purpose" rows="3" class="input-field py-3.5 resize-none w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 font-semibold" placeholder="Misal: Ziarah Walisongo, Study Tour, Gathering Perusahaan..." minlength="5" required>{{ old('purpose') }}</textarea>
                        </div>

                        {{-- Contact Info --}}
                        <div class="pt-6 border-t border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-5">Informasi Kontak Pemesan</p>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2.5">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <input type="text" name="contact_name" class="input-field py-3.5 w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 font-semibold" value="{{ old('contact_name', auth()->user()->name ?? '') }}" pattern="[a-zA-Z\s\.\'\-]+" title="Nama hanya boleh berisi huruf" oninput="this.value = this.value.replace(/[^a-zA-Z\s\.\'\-]/g, '')" required>
                                </div>
                                <div class="space-y-2.5">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                                    <input type="tel" name="contact_phone" class="input-field py-3.5 w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 font-semibold" placeholder="08xx-xxxx-xxxx" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" pattern="[0-9\+\-]+" minlength="10" maxlength="15" title="Format nomor tidak valid, hanya boleh angka" oninput="this.value = this.value.replace(/[^0-9\+\-]/g, '')" required>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" id="submit-btn"
                            class="btn-primary w-full py-4 font-bold text-base flex items-center justify-center gap-3 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[.98] transition-all">
                            <span id="btn-text">Konfirmasi Sewa Sekarang</span>
                            <svg id="btn-spinner" class="hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- ── SIDEBAR ── --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Tutorial Cara Memesan --}}
                <div class="side-card bg-white">
                    <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-100">
                        <div class="w-1.5 h-7 bg-gradient-to-b from-red-600 to-red-400 rounded-full"></div>
                        <h4 class="font-black text-lg text-gray-900">Cara Memesan</h4>
                    </div>
                    <div class="space-y-8">
                        @foreach([
                            ['Pilih Armada','Pilih bus sesuai jumlah rombongan Anda'],
                            ['Isi Detail Perjalanan','Tentukan tanggal, lokasi jemput, dan tujuan'],
                            ['Konfirmasi Admin','Tim kami akan menghubungi untuk detail harga'],
                            ['Pembayaran','Lakukan pembayaran DP sesuai instruksi admin']
                        ] as $index => $s)
                        <div class="step-item">
                            <div class="step-number">{{ $index + 1 }}</div>
                            <h5 class="font-bold text-gray-900 mb-0.5 text-sm">{{ $s[0] }}</h5>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $s[1] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- WhatsApp Support --}}
                <div class="side-card text-white text-center relative overflow-hidden" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);">
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </div>
                        <h4 class="text-xl font-black mb-2">Butuh Bantuan?</h4>
                        <p class="text-sm text-white/90 mb-6 font-medium leading-relaxed">Konsultasi langsung dengan admin kami</p>
                        <a href="https://wa.me/6281121100025" target="_blank"
                            class="block w-full py-3.5 bg-white font-bold rounded-xl hover:bg-gray-50 transition-all shadow-lg hover:scale-105 active:scale-95"
                            style="color: #128C7E;">
                            Chat Admin Sekarang
                        </a>
                    </div>
                </div>

                {{-- Important Notice --}}
                <div class="side-card bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/50">
                    <div class="flex gap-3 items-start">
                        <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-amber-900 uppercase tracking-wider mb-1">Penting</p>
                            <p class="text-xs text-amber-800 leading-relaxed">Harga yang tertera merupakan estimasi. Harga final akan dikonfirmasi admin berdasarkan rute spesifik.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Bus Capacity Validation ──
    const busSelector = document.getElementById('bus_selector');
    const paxInput = document.getElementById('passenger_count');
    const warning = document.getElementById('capacity_warning');
    const submitBtn = document.getElementById('submit-btn');

    function validateCapacity() {
        const option = busSelector.options[busSelector.selectedIndex];
        if (!option || !option.value) return;

        const capacity = parseInt(option.dataset.capacity) || 0;
        let pax = parseInt(paxInput.value) || 0;

        paxInput.setAttribute('max', capacity);

        if (pax > capacity) {
            paxInput.value = capacity;
            pax = capacity;
            
            warning.textContent = `⚠️ Kapasitas maksimal bus ini adalah ${capacity} kursi.`;
            warning.classList.remove('hidden');
            
            setTimeout(() => warning.classList.add('hidden'), 3000);
        } else {
            warning.classList.add('hidden');
        }
        
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        paxInput.classList.remove('border-red-500', 'bg-red-50');
    }

    busSelector.addEventListener('change', validateCapacity);
    paxInput.addEventListener('input', validateCapacity);

    // ── Leaflet Map Setup ──
    const mapElement = document.getElementById('rental-map');
    if (mapElement) {
        const mapStyle = document.createElement('style');
        mapStyle.textContent = '.leaflet-container img { max-width: none !important; max-height: none !important; width: auto !important; }';
        document.head.appendChild(mapStyle);

        const map = L.map('rental-map', { scrollWheelZoom: false }).setView([-7.9839, 112.6214], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19
        }).addTo(map);

        const markerIcon = L.divIcon({
            className: '',
            html: '<div style="width:20px;height:20px;border-radius:50%;background:#dc2626;border:4px solid #fff;box-shadow:0 4px 10px rgba(220,38,38,0.4)"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        let mainMarker = L.marker([-7.9839, 112.6214], { draggable: true, icon: markerIcon }).addTo(map);
        const inputPickup = document.getElementById('pickup_location');
        const inputLat = document.getElementById('latitude');
        const inputLng = document.getElementById('longitude');

        function fetchAddress(lat, lng) {
            inputLat.value = lat;
            inputLng.value = lng;
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) inputPickup.value = data.display_name;
                })
                .catch(err => console.log('Geocoding error:', err));
        }

        map.on('click', e => {
            mainMarker.setLatLng(e.latlng);
            fetchAddress(e.latlng.lat, e.latlng.lng);
        });

        mainMarker.on('dragend', () => {
            const position = mainMarker.getLatLng();
            fetchAddress(position.lat, position.lng);
        });

        document.getElementById('btn-locate').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    const coords = [pos.coords.latitude, pos.coords.longitude];
                    map.flyTo(coords, 16);
                    mainMarker.setLatLng(coords);
                    fetchAddress(coords[0], coords[1]);
                });
            }
        });

        setTimeout(() => map.invalidateSize(), 800);
    }

    // ── Submit Handling ──
    const rentalForm = document.querySelector('form');
    if (rentalForm) {
        rentalForm.addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            const text = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');
            btn.disabled = true;
            text.textContent = 'Mengirim Pengajuan...';
            spinner.classList.remove('hidden');
        });
    }
});
</script>
@endpush
