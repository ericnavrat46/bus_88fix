@extends('layouts.app')
@section('title', 'Promo & Penawaran Spesial - Bus 88')
@section('content')
<div class="bg-gray-warm-50 min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-16">
            <h1 class="text-4xl lg:text-6xl font-black text-dark mb-4">Promo <span class="text-gradient-merah">Terbaik</span> Untuk Anda</h1>
            <p class="text-gray-warm-500 text-lg max-w-2xl mx-auto">Gunakan kode promo dan nikmati diskon spesial untuk setiap perjalanan Anda bersama Bus 88.</p>
        </div>

        {{-- Promo Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($promos as $promo)
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-xl shadow-gray-warm-200/50 group hover:shadow-2xl hover:shadow-merah-600/10 transition-all duration-500 border border-gray-warm-100 flex flex-col">
                {{-- Image --}}
                <div class="relative aspect-[16/9] overflow-hidden">
                    <img src="{{ asset($promo->image_url) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $promo->title }}">
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur-md text-merah-600 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest shadow-sm">
                            {{ $promo->promo_code }}
                        </span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-dark mb-3 group-hover:text-merah-600 transition-colors">{{ $promo->title }}</h3>
                    <p class="text-gray-warm-500 text-sm mb-6 line-clamp-2">{{ $promo->description ?? 'Nikmati penawaran spesial ini untuk perjalanan Anda berikutnya.' }}</p>
                    
                    <div class="mt-auto pt-6 border-t border-gray-warm-50 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-warm-400 font-bold uppercase tracking-widest">Berlaku Hingga</span>
                            <span class="text-sm font-bold text-dark">{{ $promo->end_date->translatedFormat('d M Y') }}</span>
                        </div>
                        <a href="{{ route('promos.show', $promo) }}" class="bg-merah-50 text-merah-600 hover:bg-merah-600 hover:text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="max-w-xs mx-auto">
                    <svg class="w-20 h-20 text-gray-warm-200 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 8V4"/></svg>
                    <p class="text-gray-warm-400 font-medium">Saat ini belum ada promo aktif. Cek kembali nanti ya!</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
