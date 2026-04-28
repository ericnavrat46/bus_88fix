@extends('layouts.app')
@section('title', $promo->title . ' - Bus 88')
@section('content')
<div class="bg-gray-warm-50 min-h-screen py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex mb-8 text-sm font-medium" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-gray-warm-400 hover:text-merah-600 transition-colors">Beranda</a></li>
                <li class="text-gray-warm-300">/</li>
                <li><a href="{{ route('promos.index') }}" class="text-gray-warm-400 hover:text-merah-600 transition-colors">Promo</a></li>
                <li class="text-gray-warm-300">/</li>
                <li class="text-dark">{{ $promo->title }}</li>
            </ol>
        </nav>

        <div class="bg-white rounded-[3rem] overflow-hidden shadow-2xl shadow-gray-warm-200/50 border border-gray-warm-100">
            {{-- Big Image --}}
            <div class="relative" style="aspect-ratio: 21/9;">
                <img src="{{ $promo->image_url }}" class="w-full h-full object-cover" alt="{{ $promo->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-dark/60 via-transparent to-transparent"></div>
            </div>

            {{-- Detail Content --}}
            <div class="p-8 md:p-16">
                <div class="flex flex-wrap items-center justify-between gap-6 mb-10">
                    <div>
                        <h1 class="text-3xl md:text-5xl font-black text-dark mb-3">{{ $promo->title }}</h1>
                        <div class="flex items-center gap-4 text-gray-warm-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-5 h-5 text-merah-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Berlaku: {{ $promo->start_date->translatedFormat('d M') }} - {{ $promo->end_date->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Promo Code Box --}}
                    <div class="bg-gray-warm-50 border-2 border-dashed border-merah-200 rounded-[2rem] p-6 flex flex-col items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-warm-400">Kode Promo</span>
                        <div class="flex items-center gap-4">
                            <span class="text-3xl font-black text-dark tracking-tighter">{{ $promo->promo_code }}</span>
                            <button onclick="copyPromoCode('{{ $promo->promo_code }}')" class="bg-merah-600 text-white px-6 py-2 rounded-xl text-sm font-black hover:bg-merah-700 transition-all active:scale-95 shadow-lg shadow-merah-600/20">
                                SALIN
                            </button>
                        </div>
                    </div>
                </div>

                <div class="prose prose-lg max-w-none text-gray-warm-600 mb-12">
                    <h4 class="text-dark font-bold mb-4">Tentang Promo Ini</h4>
                    <p>{{ $promo->description ?? 'Nikmati kenyamanan perjalanan bersama Bus 88 dengan penawaran spesial ini. Masukkan kode promo saat melakukan pemesanan untuk mendapatkan potongan harga secara instan.' }}</p>
                    
                    <h4 class="text-dark font-bold mt-8 mb-4">Syarat & Ketentuan</h4>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Promo berlaku untuk semua rute Bus 88.</li>
                        <li>Satu kode promo hanya dapat digunakan satu kali per akun.</li>
                        <li>Periode pemesanan dan keberangkatan sesuai dengan periode promo yang tertera.</li>
                        <li>Promo tidak dapat digabungkan dengan penawaran lainnya.</li>
                        <li>Bus 88 berhak mengubah syarat dan ketentuan tanpa pemberitahuan sebelumnya.</li>
                    </ul>
                </div>

                <div class="flex gap-4 pt-10 border-t border-gray-warm-100">
                    <a href="{{ $promo->link ?? route('home') }}" class="btn-primary flex-1 py-5 text-lg shadow-xl shadow-merah-600/30">Gunakan Promo Sekarang</a>
                    <a href="{{ route('promos.index') }}" class="btn-secondary px-10 py-5 text-lg">Daftar Promo Lain</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection
