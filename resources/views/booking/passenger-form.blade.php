@extends('layouts.app')
@section('title', 'Data Penumpang - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Steps --}}
        <div class="flex items-center justify-center gap-0 mb-10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                <span class="text-sm font-semibold text-emerald-600">Pilih Kursi</span>
            </div>
            <div class="w-12 h-0.5 bg-merah-600 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-merah-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                <span class="text-sm font-semibold text-merah-600">Data Penumpang</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-warm-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-warm-200 text-gray-warm-500 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                <span class="text-sm font-medium text-gray-warm-400">Pembayaran</span>
            </div>
        </div>

        <div class="card p-8">
            <h2 class="text-xl font-bold text-dark mb-2">Data Penumpang</h2>
            <p class="text-gray-warm-500 mb-8">Lengkapi data untuk {{ count($selectedSeats) }} penumpang</p>

            <form method="POST" action="{{ route('booking.store', $schedule) }}" class="space-y-6">
                @csrf
                @foreach($selectedSeats as $index => $seat)
                <div class="p-6 bg-gray-warm-50 rounded-2xl border border-gray-warm-100">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-merah-600 text-white rounded-xl flex items-center justify-center font-bold text-sm">{{ $seat }}</div>
                        <div>
                            <h3 class="font-bold text-dark">Penumpang Kursi {{ $seat }}</h3>
                            <p class="text-xs text-gray-warm-500">Kursi #{{ $seat }}</p>
                        </div>
                    </div>
                    <input type="hidden" name="passengers[{{ $index }}][seat_number]" value="{{ $seat }}">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="label-field">Nama Lengkap *</label>
                            <input type="text" name="passengers[{{ $index }}][passenger_name]" class="input-field" placeholder="Sesuai KTP" required>
                        </div>
                        <div>
                            <label class="label-field">No. KTP/Identitas</label>
                            <input type="text" name="passengers[{{ $index }}][id_number]" class="input-field" placeholder="Opsional">
                        </div>
                        <div>
                            <label class="label-field">No. Telepon</label>
                            <input type="text" name="passengers[{{ $index }}][phone]" class="input-field" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Summary --}}
                <div class="p-6 bg-merah-50 rounded-2xl border border-merah-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-warm-600">{{ count($selectedSeats) }} kursi × Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                        <span class="text-xl font-black text-merah-600">Rp {{ number_format($schedule->price * count($selectedSeats), 0, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-gray-warm-500">{{ $schedule->route->origin }} → {{ $schedule->route->destination }} • {{ $schedule->departure_date->translatedFormat('d M Y') }}</p>
                </div>

                <button type="submit" class="btn-primary w-full text-center text-lg py-4">
                    Lanjut ke Pembayaran
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
