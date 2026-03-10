@extends('layouts.app')
@section('title', 'Pilih Kursi - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-warm-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-merah-600">Beranda</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span>Cari Jadwal</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-dark font-medium">Pilih Kursi</span>
        </nav>

        {{-- Steps --}}
        <div class="flex items-center justify-center gap-0 mb-10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-merah-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                <span class="text-sm font-semibold text-merah-600">Pilih Kursi</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-warm-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-warm-200 text-gray-warm-500 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                <span class="text-sm font-medium text-gray-warm-400">Data Penumpang</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-warm-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-warm-200 text-gray-warm-500 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                <span class="text-sm font-medium text-gray-warm-400">Pembayaran</span>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Seat Map --}}
            <div class="lg:col-span-2">
                <div class="card p-8" x-data="seatSelector()">
                    <h2 class="text-xl font-bold text-dark mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Pilih Nomor Kursi
                    </h2>

                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center gap-6 mb-8 p-4 bg-gray-warm-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gray-warm-100 border-2 border-gray-warm-200 rounded-lg"></div>
                            <span class="text-xs font-medium text-gray-warm-600">Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-merah-600 rounded-lg"></div>
                            <span class="text-xs font-medium text-gray-warm-600">Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gray-warm-300 rounded-lg"></div>
                            <span class="text-xs font-medium text-gray-warm-600">Terisi</span>
                        </div>
                    </div>

                    {{-- Bus Seat Layout --}}
                    <div class="bg-gray-warm-50 rounded-2xl p-6">
                        {{-- Driver --}}
                        <div class="flex justify-end mb-6">
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-warm-200 rounded-lg text-xs font-semibold text-gray-warm-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Supir
                            </div>
                        </div>

                        {{-- Seats Grid (2-2 layout) --}}
                        <div class="max-w-xs mx-auto space-y-3">
                            @php
                                $totalSeats = $schedule->bus->capacity;
                                $rows = ceil($totalSeats / 4);
                            @endphp
                            @for($row = 0; $row < $rows; $row++)
                                <div class="flex items-center justify-center gap-6">
                                    {{-- Left pair --}}
                                    <div class="flex gap-2">
                                        @for($col = 0; $col < 2; $col++)
                                            @php $seatNum = $row * 4 + $col + 1; @endphp
                                            @if($seatNum <= $totalSeats)
                                                @if(in_array((string)$seatNum, $bookedSeats))
                                                    <div class="w-10 h-10 bg-gray-warm-300 rounded-lg flex items-center justify-center text-xs font-bold text-gray-warm-500 cursor-not-allowed">
                                                        {{ $seatNum }}
                                                    </div>
                                                @else
                                                    <button type="button"
                                                        @click="toggleSeat('{{ $seatNum }}')"
                                                        :class="selected.includes('{{ $seatNum }}') ? 'bg-merah-600 text-white shadow-lg shadow-merah-600/30' : 'bg-gray-warm-100 border-2 border-gray-warm-200 text-gray-warm-700 hover:border-merah-400 hover:bg-merah-50'"
                                                        class="w-10 h-10 rounded-lg flex items-center justify-center text-xs font-bold transition-all duration-200 cursor-pointer">
                                                        {{ $seatNum }}
                                                    </button>
                                                @endif
                                            @endif
                                        @endfor
                                    </div>
                                    {{-- Aisle --}}
                                    <div class="w-6"></div>
                                    {{-- Right pair --}}
                                    <div class="flex gap-2">
                                        @for($col = 2; $col < 4; $col++)
                                            @php $seatNum = $row * 4 + $col + 1; @endphp
                                            @if($seatNum <= $totalSeats)
                                                @if(in_array((string)$seatNum, $bookedSeats))
                                                    <div class="w-10 h-10 bg-gray-warm-300 rounded-lg flex items-center justify-center text-xs font-bold text-gray-warm-500 cursor-not-allowed">
                                                        {{ $seatNum }}
                                                    </div>
                                                @else
                                                    <button type="button"
                                                        @click="toggleSeat('{{ $seatNum }}')"
                                                        :class="selected.includes('{{ $seatNum }}') ? 'bg-merah-600 text-white shadow-lg shadow-merah-600/30' : 'bg-gray-warm-100 border-2 border-gray-warm-200 text-gray-warm-700 hover:border-merah-400 hover:bg-merah-50'"
                                                        class="w-10 h-10 rounded-lg flex items-center justify-center text-xs font-bold transition-all duration-200 cursor-pointer">
                                                        {{ $seatNum }}
                                                    </button>
                                                @endif
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Submit --}}
                    <form method="POST" action="{{ route('booking.passenger-form', $schedule) }}" class="mt-6">
                        @csrf
                        <template x-for="seat in selected" :key="seat">
                            <input type="hidden" name="seats[]" :value="seat">
                        </template>
                        <button type="submit" :disabled="selected.length === 0" class="btn-primary w-full text-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="selected.length > 0 ? 'Lanjutkan (' + selected.length + ' kursi)' : 'Pilih kursi terlebih dahulu'"></span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Trip Summary --}}
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-dark mb-4">Detail Perjalanan</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-warm-400 font-medium">Tanggal</p>
                                <p class="text-sm font-semibold text-dark">{{ $schedule->departure_date->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-warm-400 font-medium">Jam</p>
                                <p class="text-sm font-semibold text-dark">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-warm-400 font-medium">Rute</p>
                                <p class="text-sm font-semibold text-dark">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-merah-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-warm-400 font-medium">Bus</p>
                                <p class="text-sm font-semibold text-dark">{{ $schedule->bus->name }}</p>
                                <span class="badge {{ $schedule->bus->type === 'eksekutif' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }} mt-1">{{ ucfirst($schedule->bus->type) }}</span>
                            </div>
                        </div>
                        <hr class="border-gray-warm-100">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-warm-500">Harga per kursi</span>
                            <span class="text-lg font-black text-merah-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function seatSelector() {
    return {
        selected: [],
        toggleSeat(seat) {
            const index = this.selected.indexOf(seat);
            if (index > -1) {
                this.selected.splice(index, 1);
            } else {
                if (this.selected.length >= 5) {
                    alert('Maksimal 5 kursi per booking');
                    return;
                }
                this.selected.push(seat);
            }
        }
    }
}
</script>
@endpush
@endsection
