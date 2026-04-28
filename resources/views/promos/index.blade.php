@extends('layouts.app')
@section('title', 'Promo & Penawaran Spesial - Bus 88')
@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header & Filters --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4 border-b border-gray-200 pb-4">
            <div class="flex items-center gap-3 text-dark">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h1 class="text-xl md:text-2xl font-bold">Promo & Penawaran Spesial - Semua ({{ $promos->count() }})</h1>
            </div>

            <div class="flex items-center gap-3">
                {{-- Filter Button --}}
                <div class="relative group cursor-pointer" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter: {{ request('filter') == 'bus' ? 'Tiket Bus' : (request('filter') == 'wisata' ? 'Paket Wisata' : 'Semua Kategori') }}
                        <svg class="w-4 h-4 ml-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    {{-- Dropdown --}}
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-20" style="display: none;">
                        <ul class="py-1 text-sm text-gray-700">
                            <li><a href="{{ request()->fullUrlWithQuery(['filter' => null]) }}" class="block px-4 py-2 hover:bg-gray-100 {{ !request('filter') ? 'font-bold text-red-600 bg-red-50' : '' }}">Semua Kategori</a></li>
                            <li><a href="{{ request()->fullUrlWithQuery(['filter' => 'bus']) }}" class="block px-4 py-2 hover:bg-gray-100 {{ request('filter') == 'bus' ? 'font-bold text-red-600 bg-red-50' : '' }}">Tiket Bus</a></li>
                            <li><a href="{{ request()->fullUrlWithQuery(['filter' => 'wisata']) }}" class="block px-4 py-2 hover:bg-gray-100 {{ request('filter') == 'wisata' ? 'font-bold text-red-600 bg-red-50' : '' }}">Paket Wisata</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Sort Button --}}
                <div class="relative group cursor-pointer" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                        Urutkan: {{ request('sort') == 'terbaru' ? 'Terbaru' : (request('sort') == 'segera_berakhir' ? 'Segera Berakhir' : 'Standar') }}
                        <svg class="w-4 h-4 ml-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    {{-- Dropdown --}}
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-20" style="display: none;">
                        <ul class="py-1 text-sm text-gray-700">
                            <li><a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="block px-4 py-2 hover:bg-gray-100 {{ !request('sort') ? 'font-bold text-red-600 bg-red-50' : '' }}">Standar</a></li>
                            <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}" class="block px-4 py-2 hover:bg-gray-100 {{ request('sort') == 'terbaru' ? 'font-bold text-red-600 bg-red-50' : '' }}">Terbaru</a></li>
                            <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'segera_berakhir']) }}" class="block px-4 py-2 hover:bg-gray-100 {{ request('sort') == 'segera_berakhir' ? 'font-bold text-red-600 bg-red-50' : '' }}">Segera Berakhir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Promo Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($promos as $promo)
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 flex flex-col group">
                {{-- Image --}}
                <div class="relative overflow-hidden bg-gray-100" style="aspect-ratio: 16/9;">
                    <img src="{{ $promo->image_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $promo->title }}">
                    @if(now()->diffInDays($promo->end_date) <= 7)
                    <div class="absolute top-2 right-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-sm">
                        Sisa {{ now()->diffInDays($promo->end_date) }} Hari
                    </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5 flex-1 flex flex-col">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 mb-1 font-medium">Periode promo</span>
                            <span class="text-sm font-semibold text-dark">{{ $promo->start_date->translatedFormat('j M') }} - {{ $promo->end_date->translatedFormat('j M Y') }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 mb-1 font-medium">Min. transaksi</span>
                            <span class="text-sm font-semibold text-dark">Tergantung produk</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('promos.show', $promo) }}" class="mt-auto block w-full text-center bg-red-600 text-white py-2.5 rounded-lg text-sm font-bold hover:bg-red-700 transition-colors shadow-sm">
                        Lihat Promo
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="max-w-xs mx-auto">
                    <svg class="w-20 h-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 8V4"/></svg>
                    <p class="text-gray-500 font-medium">Saat ini belum ada promo aktif. Cek kembali nanti ya!</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
