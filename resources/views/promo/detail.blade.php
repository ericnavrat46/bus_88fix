@extends('layouts.app')

@section('title', "Rincian Promo: {$flashSale->title}")

@push('styles')
<style>
    .sticky-bottom-btn {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        background: linear-gradient(to top, rgba(255,255,255,1) 80%, rgba(255,255,255,0));
        z-index: 50;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen pb-24">
    {{-- Header --}}
    <div class="bg-white border-b sticky top-0 z-40">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center gap-4">
            <a href="{{ route('home') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Rincian Promo</h1>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        {{-- Banner --}}
        <div class="px-4 pt-6">
            <div class="rounded-2xl overflow-hidden shadow-lg aspect-video bg-gradient-to-br from-merah-500 to-amber-500">
                @if($flashSale->banner_url)
                    <img src="{{ $flashSale->banner_url }}" alt="{{ $flashSale->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-white">
                        <svg class="w-20 h-20 opacity-20 mb-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"/></svg>
                        <span class="text-2xl font-black uppercase tracking-widest">{{ $flashSale->title }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info Content --}}
        <div class="px-4 py-8">
            <h2 class="text-2xl font-black text-gray-900 mb-6">{{ $flashSale->title }}</h2>

            {{-- Info Card (Like DAMRI) --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-black text-gray-900 mb-1">Periode Pembelian:</p>
                        <p class="text-base text-gray-600">
                            {{ $flashSale->start_time->translatedFormat('d F Y H:i') }} — {{ $flashSale->end_time->translatedFormat('d F Y H:i') }}
                        </p>
                    </div>

                    @if($flashSale->target_type === 'schedule')
                    <div>
                        <p class="text-sm font-black text-gray-900 mb-1">Item Promo:</p>
                        <div class="flex items-center gap-2 text-base text-gray-600">
                            <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span>{{ $flashSale->target->route->origin }} → {{ $flashSale->target->route->destination }}</span>
                        </div>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm font-black text-gray-900 mb-1">Kuota Tersisa:</p>
                        <p class="text-base text-gray-600">
                            {{ max(0, $flashSale->quota - $flashSale->used_quota) }} Orang
                        </p>
                    </div>
                </div>
            </div>

            {{-- Description & Terms --}}
            @if($flashSale->description)
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Tentang Promo</h3>
                <div class="prose prose-sm text-gray-600 max-w-none">
                    {!! nl2br(e($flashSale->description)) !!}
                </div>
            </div>
            @endif

            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Syarat dan Ketentuan</h3>
                <div class="space-y-3">
                    @if($flashSale->terms_conditions)
                        @foreach(explode("\n", str_replace("\r", "", $flashSale->terms_conditions)) as $line)
                            @if(trim($line))
                                <div class="flex gap-3">
                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-gray-600 text-sm">{{ trim($line) }}</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="flex gap-3">
                            <div class="w-1.5 h-1.5 bg-gray-300 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-gray-600 text-sm">Promo berlaku selama kuota masih tersedia.</p>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-1.5 h-1.5 bg-gray-300 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-gray-600 text-sm">Tiket yang dibeli dengan promo tidak dapat dibatalkan atau direfund.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky Floating Button --}}
    <div class="sticky-bottom-btn">
        <div class="max-w-2xl mx-auto px-4">
            <a href="{{ route('home') }}" class="w-full bg-merah-600 hover:bg-merah-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-merah-600/20 flex items-center justify-center gap-2 transition-all hover:scale-[1.02] active:scale-100">
                <span>Cari Tiket</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </a>
        </div>
    </div>
</div>
@endsection
