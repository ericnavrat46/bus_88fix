@extends('layouts.app')
@section('title', "Jadwal Bus {$origin} - {$destination}")
@section('content')
<div class="bg-gradient-to-b from-gray-50 to-cream min-h-screen overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Breadcrumb & Header --}}
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-gray-warm-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-merah-600 transition-colors">Beranda</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-dark font-medium">Jadwal</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
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
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-8 items-start">
            {{-- ══════════════════════════════════════ --}}
            {{-- SIDEBAR FILTER --}}
            {{-- ══════════════════════════════════════ --}}
            <div class="w-full flex-shrink-0 space-y-6" style="max-width: 320px;">
                {{-- Form Ubah Pencarian --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm" x-data="{ tripType: '{{ $tripType }}' }">
                    <h3 class="text-xl font-bold text-dark mb-4">Ubah Pencarian</h3>
                    
                    <form action="{{ route('schedules.search') }}" method="GET" class="space-y-4">
                        <input type="hidden" name="trip_type" :value="tripType">
                        
                        {{-- Trip Type Toggle --}}
                        <div class="flex items-center bg-gray-warm-50 rounded-xl p-1 mb-2">
                            <button type="button" @click="tripType = 'one_way'"
                                    :class="tripType === 'one_way' ? 'bg-white text-merah-600 shadow-sm' : 'text-gray-warm-500 hover:text-gray-warm-700'"
                                    class="flex-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded-lg text-xs font-semibold transition-all">
                                Sekali Jalan
                            </button>
                            <button type="button" @click="tripType = 'round_trip'"
                                    :class="tripType === 'round_trip' ? 'bg-white text-merah-600 shadow-sm' : 'text-gray-warm-500 hover:text-gray-warm-700'"
                                    class="flex-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded-lg text-xs font-semibold transition-all">
                                Pulang Pergi
                            </button>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-warm-500 uppercase tracking-wide mb-1">Kota Asal</label>
                            <select name="origin" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-merah-500 outline-none" required>
                                <option value="">Pilih Asal</option>
                                @foreach($origins ?? [] as $orig)
                                    <option value="{{ $orig }}" {{ $origin == $orig ? 'selected' : '' }}>{{ $orig }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-warm-500 uppercase tracking-wide mb-1">Kota Tujuan</label>
                            <select name="destination" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-merah-500 outline-none" required>
                                <option value="">Pilih Tujuan</option>
                                @foreach($destinations ?? [] as $dest)
                                    <option value="{{ $dest }}" {{ $destination == $dest ? 'selected' : '' }}>{{ $dest }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-warm-500 uppercase tracking-wide mb-1">Tanggal Berangkat</label>
                            <input type="date" name="date" value="{{ $date }}" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-merah-500 outline-none" required>
                        </div>
                        <div x-show="tripType === 'round_trip'" x-cloak>
                            <label class="block text-xs font-bold text-gray-warm-500 uppercase tracking-wide mb-1">Tanggal Pulang</label>
                            <input type="date" name="return_date" value="{{ $returnDate ?? date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-merah-500 outline-none" :required="tripType === 'round_trip'">
                        </div>
                        <button type="submit" class="w-full bg-merah-600 hover:bg-merah-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors mt-2 shadow-sm shadow-merah-600/30">
                            Cari Jadwal Baru
                        </button>
                    </form>
                </div>

                {{-- Filter Card --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <form action="{{ route('schedules.search') }}" method="GET" id="filter-form">
                        <input type="hidden" name="origin" value="{{ $origin }}">
                        <input type="hidden" name="destination" value="{{ $destination }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="trip_type" value="{{ $tripType }}">
                        @if($returnDate)
                            <input type="hidden" name="return_date" value="{{ $returnDate }}">
                        @endif

                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-dark">Filter</h3>
                            <button type="submit" class="text-xs font-bold text-merah-600 hover:text-merah-700">Terapkan</button>
                        </div>

                        {{-- Waktu --}}
                        <div class="mb-6">
                            <h4 class="text-[10px] font-bold text-gray-warm-500 uppercase tracking-widest mb-3">Waktu Keberangkatan</h4>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="waktu[]" value="pagi" {{ in_array('pagi', request('waktu', [])) ? 'checked' : '' }} class="w-4 h-4 text-merah-600 border-gray-300 rounded focus:ring-merah-500" onchange="document.getElementById('filter-form').submit()">
                                    <span class="text-sm text-gray-warm-700 group-hover:text-dark">Pagi (06:00 - 12:00)</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="waktu[]" value="siang" {{ in_array('siang', request('waktu', [])) ? 'checked' : '' }} class="w-4 h-4 text-merah-600 border-gray-300 rounded focus:ring-merah-500" onchange="document.getElementById('filter-form').submit()">
                                    <span class="text-sm text-gray-warm-700 group-hover:text-dark">Siang (12:00 - 18:00)</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="waktu[]" value="malam" {{ in_array('malam', request('waktu', [])) ? 'checked' : '' }} class="w-4 h-4 text-merah-600 border-gray-300 rounded focus:ring-merah-500" onchange="document.getElementById('filter-form').submit()">
                                    <span class="text-sm text-gray-warm-700 group-hover:text-dark">Malam (18:00 - 00:00)</span>
                                </label>
                            </div>
                        </div>

                        {{-- Nama Bus --}}
                        <div class="mb-6">
                            <h4 class="text-[10px] font-bold text-gray-warm-500 uppercase tracking-widest mb-3">Nama Bus</h4>
                            <select name="bus_id[]" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-merah-500 outline-none" onchange="document.getElementById('filter-form').submit()">
                                <option value="">Semua Bus</option>
                                @foreach($availableBuses ?? [] as $bus)
                                    <option value="{{ $bus->id }}" {{ in_array($bus->id, request('bus_id', [])) ? 'selected' : '' }}>
                                        {{ $bus->name }} ({{ ucfirst($bus->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Harga --}}
                        <div class="mb-2">
                            <h4 class="text-[10px] font-bold text-gray-warm-500 uppercase tracking-widest mb-3">Range Harga</h4>
                            <select name="harga" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-merah-500 outline-none" onchange="document.getElementById('filter-form').submit()">
                                <option value="">Semua Harga</option>
                                <option value="0-50000" {{ request('harga') == '0-50000' ? 'selected' : '' }}>Rp 0 - Rp 50.000</option>
                                <option value="50000-100000" {{ request('harga') == '50000-100000' ? 'selected' : '' }}>Rp 50.000 - Rp 100.000</option>
                                <option value="100000-150000" {{ request('harga') == '100000-150000' ? 'selected' : '' }}>Rp 100.000 - Rp 150.000</option>
                                <option value="150000-200000" {{ request('harga') == '150000-200000' ? 'selected' : '' }}>Rp 150.000 - Rp 200.000</option>
                                <option value="200000-300000" {{ request('harga') == '200000-300000' ? 'selected' : '' }}>Rp 200.000 - Rp 300.000</option>
                                <option value="300000-500000" {{ request('harga') == '300000-500000' ? 'selected' : '' }}>Rp 300.000 - Rp 500.000</option>
                                <option value="500000-9999999" {{ request('harga') == '500000-9999999' ? 'selected' : '' }}>Lebih dari Rp 500.000</option>
                            </select>
                        </div>
                    </form>
                </div>

                {{-- Promo Banner --}}
                <div class="rounded-2xl overflow-hidden relative shadow-lg group cursor-pointer h-64">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/90 via-dark/40 to-transparent z-10"></div>
                    <img src="{{ asset('assets/img/bus/bus-1.jpg') }}" alt="Lounge" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=800&auto=format&fit=crop'">
                    <div class="absolute bottom-0 left-0 right-0 p-5 z-20">
                        <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mb-1">Tingkatkan Sekarang</p>
                        <h4 class="text-white font-bold text-lg leading-tight mb-2">Akses Lounge Kelas Utama</h4>
                        <p class="text-white/80 text-xs">Nikmati minuman gratis dan kursi malas ultra lebar.</p>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════ --}}
            {{-- MAIN CONTENT (SCHEDULES) --}}
            {{-- ══════════════════════════════════════ --}}
            <div class="flex-1 min-w-0">
                
                {{-- Header count & sort --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <h2 class="text-2xl font-black text-dark">{{ $schedules->count() + ($tripType === 'round_trip' && isset($returnSchedules) ? $returnSchedules->count() : 0) }} Jadwal Tersedia</h2>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-gray-warm-500">Urutkan berdasarkan:</span>
                        <button class="font-bold text-merah-600 flex items-center gap-1 hover:text-merah-700">
                            Keberangkatan Terawal
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- OUTBOUND --}}
                @if($schedules->isEmpty())
                    <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center mb-8 shadow-sm">
                        <div class="w-20 h-20 bg-merah-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-merah-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">Tidak Ada Jadwal Berangkat</h3>
                        <p class="text-gray-warm-500 mb-6">Maaf, belum ada jadwal tersedia untuk rute dan tanggal ini.</p>
                        <a href="{{ route('home') }}" class="btn-primary">Cari Rute Lain</a>
                    </div>
                @else
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-10">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse whitespace-nowrap">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-200 text-[11px] text-gray-warm-500 uppercase tracking-wider font-bold">
                                        <th class="px-6 py-4">Tanggal</th>
                                        <th class="px-6 py-4">Jam</th>
                                        <th class="px-6 py-4">Rute</th>
                                        <th class="px-6 py-4">Bus</th>
                                        <th class="px-6 py-4">Harga</th>
                                        <th class="px-6 py-4 text-center">Kursi</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($schedules as $schedule)
                                        @php
                                            $isPremium = $schedule->bus->type === 'eksekutif' || $schedule->price > 100000;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors {{ $isPremium ? 'bg-merah-50/20' : '' }}">
                                            <td class="px-6 py-4 text-sm font-semibold text-dark">
                                                {{ \Carbon\Carbon::parse($schedule->departure_time)->translatedFormat('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="font-bold text-dark">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</span>
                                                <span class="text-gray-warm-400 mx-1">-</span>
                                                <span class="font-bold text-dark">{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-warm-700">
                                                {{ $origin }} <span class="text-merah-500 mx-1">→</span> {{ $destination }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="font-bold text-dark block">{{ $schedule->bus->name }}</span>
                                                <span class="text-[10px] font-bold text-merah-600 uppercase tracking-wider">{{ ucfirst($schedule->bus->type) }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($schedule->active_flash_sale)
                                                    <div class="line-through text-gray-400 text-[10px] font-bold decoration-merah-500/50 mb-0.5">Rp {{ number_format($schedule->price, 0, ',', '.') }}</div>
                                                    <span class="text-sm font-black text-dark">Rp {{ number_format($schedule->final_price, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-sm font-black text-dark">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="text-sm font-bold {{ $schedule->remaining_seats <= 5 ? 'text-red-600' : 'text-gray-warm-700' }}">
                                                    {{ $schedule->remaining_seats }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($schedule->remaining_seats > 0)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-700">Tersedia</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-600">Habis</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                @if($schedule->remaining_seats > 0)
                                                    <a href="{{ route('booking.select-seat', $schedule) }}" class="inline-block px-4 py-2 rounded-lg font-bold text-xs transition-all duration-200 {{ $isPremium ? 'bg-merah-600 text-white hover:bg-merah-700 shadow-sm hover:shadow' : 'bg-white text-merah-600 border border-merah-200 hover:border-merah-600 hover:bg-merah-50' }}">
                                                        Pilih Kursi
                                                    </a>
                                                @else
                                                    <button disabled class="px-4 py-2 rounded-lg font-bold text-xs bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">Habis</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif


                {{-- RETURN SECTION (only for round_trip) --}}
                @if($tripType === 'round_trip' && $returnDate)
                    <div class="mt-12 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-dark">Jadwal Kepulangan</h2>
                                <p class="text-sm text-gray-warm-500">{{ $destination }} → {{ $origin }} • {{ \Carbon\Carbon::parse($returnDate)->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($returnSchedules->isEmpty())
                        <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center shadow-sm">
                            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-xl font-bold text-dark mb-2">Tidak Ada Jadwal Pulang</h3>
                            <p class="text-gray-warm-500 mb-2">Tidak ada jadwal dari {{ $destination }} ke {{ $origin }} pada tanggal {{ \Carbon\Carbon::parse($returnDate)->translatedFormat('d F Y') }}.</p>
                        </div>
                    @else
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-10">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse whitespace-nowrap">
                                    <thead>
                                        <tr class="bg-gray-50/80 border-b border-gray-200 text-[11px] text-gray-warm-500 uppercase tracking-wider font-bold">
                                            <th class="px-6 py-4">Tanggal</th>
                                            <th class="px-6 py-4">Jam</th>
                                            <th class="px-6 py-4">Rute</th>
                                            <th class="px-6 py-4">Bus</th>
                                            <th class="px-6 py-4">Harga</th>
                                            <th class="px-6 py-4 text-center">Kursi</th>
                                            <th class="px-6 py-4 text-center">Status</th>
                                            <th class="px-6 py-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($returnSchedules as $schedule)
                                            @php
                                                $isPremium = $schedule->bus->type === 'eksekutif' || $schedule->price > 100000;
                                            @endphp
                                            <tr class="hover:bg-gray-50 transition-colors {{ $isPremium ? 'bg-blue-50/20' : '' }}">
                                                <td class="px-6 py-4 text-sm font-semibold text-dark">
                                                    {{ \Carbon\Carbon::parse($schedule->departure_time)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm">
                                                    <span class="font-bold text-dark">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</span>
                                                    <span class="text-gray-warm-400 mx-1">-</span>
                                                    <span class="font-bold text-dark">{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-warm-700">
                                                    {{ $destination }} <span class="text-blue-500 mx-1">→</span> {{ $origin }}
                                                </td>
                                                <td class="px-6 py-4 text-sm">
                                                    <span class="font-bold text-dark block">{{ $schedule->bus->name }}</span>
                                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">{{ ucfirst($schedule->bus->type) }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($schedule->active_flash_sale)
                                                        <div class="line-through text-gray-400 text-[10px] font-bold decoration-blue-500/50 mb-0.5">Rp {{ number_format($schedule->price, 0, ',', '.') }}</div>
                                                        <span class="text-sm font-black text-dark">Rp {{ number_format($schedule->final_price, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-sm font-black text-dark">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="text-sm font-bold {{ $schedule->remaining_seats <= 5 ? 'text-red-600' : 'text-gray-warm-700' }}">
                                                        {{ $schedule->remaining_seats }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($schedule->remaining_seats > 0)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-700">Tersedia</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-600">Habis</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    @if($schedule->remaining_seats > 0)
                                                        <a href="{{ route('booking.select-seat', $schedule) }}" class="inline-block px-4 py-2 rounded-lg font-bold text-xs transition-all duration-200 {{ $isPremium ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm hover:shadow' : 'bg-white text-blue-600 border border-blue-200 hover:border-blue-600 hover:bg-blue-50' }}">
                                                            Pilih Kursi
                                                        </a>
                                                    @else
                                                        <button disabled class="px-4 py-2 rounded-lg font-bold text-xs bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">Habis</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
