@extends('layouts.app')
@section('title', $package->name . ' - Premium Tour Bus 88')
@section('content')
<div class="bg-white min-h-screen">
    {{-- Immersive Hero --}}
    <div class="relative h-[60vh] md:h-[75vh] min-h-[500px] overflow-hidden">
        @if($package->image)
            <img src="{{ asset('storage/' . $package->image) }}" class="w-full h-full object-cover scale-105 animate-slow-zoom">
        @else
            <div class="w-full h-full bg-gradient-to-br from-merah-700 via-merah-900 to-dark"></div>
        @endif
        
        {{-- Overlays --}}
        <div class="absolute inset-0 bg-gradient-to-t from-dark via-dark/20 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <nav class="flex items-center gap-2 text-white/60 text-xs font-black uppercase tracking-widest mb-6">
                    <a href="{{ route('home') }}" class="hover:text-merah-500 transition-colors">Home</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('tour.index') }}" class="hover:text-merah-500 transition-colors">Paket Wisata</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-merah-500">{{ $package->name }}</span>
                </nav>
                
                <h1 class="text-4xl md:text-7xl font-black text-white mb-6 leading-tight tracking-tight max-w-4xl">{{ $package->name }}</h1>
                
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-white/10">
                        <div class="w-10 h-10 rounded-full bg-merah-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-white/50 font-black uppercase leading-none mb-1">DURASI</p>
                            <p class="text-sm font-bold text-white">{{ $package->duration_days }} Hari</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-white/10">
                        <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-white/50 font-black uppercase leading-none mb-1">RATING</p>
                            <p class="text-sm font-bold text-white">
                                {{ $reviews->count() > 0 ? number_format($reviews->avg('rating'), 1) : '0.0' }}
                                ({{ $reviews->count() }} Reviews)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-12 gap-16">
            {{-- Main Content --}}
            <div class="lg:col-span-8 space-y-20">
                {{-- Overview --}}
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-1.5 bg-merah-600 rounded-full"></div>
                        <h2 class="text-3xl font-black text-dark uppercase tracking-tight">Overview</h2>
                    </div>
                    <div class="prose prose-lg text-gray-500 max-w-none leading-relaxed">
                        <p class="whitespace-pre-line">{{ $package->description }}</p>
                    </div>
                </section>

                {{-- Destinations --}}
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-1.5 bg-merah-600 rounded-full"></div>
                        <h2 class="text-3xl font-black text-dark uppercase tracking-tight">Destinasi Utama</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-6">
                        @foreach($package->destinations ?? [] as $dest)
                        <div class="group flex items-center gap-6 p-6 bg-slate-50 rounded-[2rem] border border-transparent hover:border-merah-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm group-hover:bg-merah-600 group-hover:text-white transition-colors">✨</div>
                            <span class="text-lg font-bold text-dark">{{ $dest }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>

                {{-- Facilities --}}
                <section class="grid md:grid-cols-2 gap-10">
                    <div class="p-10 bg-emerald-50 rounded-[3rem] border border-emerald-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-10">
                            <svg class="w-24 h-24 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-emerald-900 mb-8 uppercase tracking-tight">Fasilitas Inti</h3>
                        <ul class="space-y-4">
                            @foreach($package->inclusions ?? [] as $item)
                            <li class="flex items-start gap-4 text-emerald-800/80 font-medium">
                                <div class="mt-1 w-5 h-5 rounded-full bg-emerald-200 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-10">
                            <svg class="w-24 h-24 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-8 uppercase tracking-tight">Ekstensi/Opsional</h3>
                        <ul class="space-y-4">
                            @foreach($package->exclusions ?? [] as $item)
                            <li class="flex items-start gap-4 text-slate-700 font-medium">
                                <div class="mt-1 w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
                {{-- Reviews --}}
                <section>
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-1.5 bg-merah-600 rounded-full"></div>
                        <h2 class="text-3xl font-black text-dark uppercase tracking-tight">Ulasan Pelanggan</h2>
                    </div>

                    @if($reviews->isEmpty())
                        <div class="py-12 px-8 bg-slate-50 rounded-[2rem] text-center border border-dashed border-gray-200">
                            <p class="text-gray-400 font-medium italic">Belum ada ulasan untuk paket ini. Jadilah yang pertama memberikan ulasan!</p>
                        </div>
                    @else
                        <div class="space-y-10">
                            @foreach($reviews as $rev)
                            <div class="group">
                                <div class="flex items-start gap-6">
                                    <div class="w-16 h-16 rounded-2xl bg-merah-50 flex items-center justify-center font-black text-merah-600 text-xl shadow-inner shrink-0 tracking-tighter">
                                        {{ strtoupper(substr($rev->user->name, 0, 2)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-bold text-dark text-lg">{{ $rev->user->name }}</h4>
                                            <span class="text-xs font-black text-gray-300 uppercase tracking-widest">{{ $rev->created_at->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 mb-4">
                                            @for($i=1; $i<=5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $rev->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 leading-relaxed italic">"{{ $rev->comment }}"</p>

                                        @if($rev->image)
                                        <div class="mt-6">
                                            <img src="{{ asset('storage/' . $rev->image) }}" class="w-48 h-32 object-cover rounded-2xl shadow-md border-4 border-white hover:scale-105 transition-transform cursor-pointer">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last)
                                <div class="mt-10 border-b border-gray-50"></div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4">
                <div class="sticky top-28 bg-white rounded-[3rem] p-10 shadow-[0_30px_100px_rgba(0,0,0,0.1)] border border-gray-50">
                    <div class="mb-10 text-center">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3">INVESTASI PERJALANAN</p>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-[1.75rem] font-medium text-dark">IDR</span>
                            <span class="text-5xl font-black text-merah-600 leading-none tracking-tight">{{ number_format($package->price_per_person, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-sm font-bold text-gray-400 mt-2">/ PAX (ALL-IN)</p>
                    </div>

                    <div class="space-y-4 mb-10">
                        <div class="flex items-center gap-4 p-5 bg-slate-50 rounded-2xl border border-transparent hover:border-gray-100 transition-all">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center">
                                <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-dark">Pasti Berangkat</p>
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider">Garansi Keberangkatan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-5 bg-slate-50 rounded-2xl border border-transparent hover:border-gray-100 transition-all">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center">
                                <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-dark">Asuransi Trip</p>
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider">Perjalanan Tanpa Khawatir</p>
                            </div>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('tour.booking', $package->slug) }}" class="flex items-center justify-center gap-3 w-full bg-dark text-white py-6 rounded-2xl font-black uppercase tracking-widest hover:bg-merah-600 hover:shadow-2xl hover:shadow-merah-200 transition-all duration-500 group">
                            Pesan Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center justify-center w-full bg-slate-100 text-dark py-6 rounded-2xl font-black uppercase tracking-widest hover:bg-dark hover:text-white transition-all duration-500">
                            Masuk Untuk Pesan
                        </a>
                    @endauth

                    <div class="mt-8 flex items-center justify-center gap-4 border-t border-gray-50 pt-8">
                        <div class="flex -space-x-3">
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-merah-100 flex items-center justify-center text-[10px] font-bold">A</div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-100 flex items-center justify-center text-[10px] font-bold">B</div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-amber-100 flex items-center justify-center text-[10px] font-bold">C</div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">500+ Orang telah berkunjung</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes slow-zoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }
    .animate-slow-zoom {
        animation: slow-zoom 20s infinite alternate linear;
    }
</style>
@endsection
