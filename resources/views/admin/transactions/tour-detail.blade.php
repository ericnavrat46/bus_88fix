@extends('layouts.admin')
@section('title', 'Detail Booking Tour - Admin')
@section('page-title', 'Detail Booking Tour')
@section('content')
<div class="space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('admin.transactions.tours') }}" class="inline-flex items-center gap-2 text-sm text-gray-warm-500 hover:text-merah-600 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Tour
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Package Info --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Hero Card --}}
            <div class="card p-0 overflow-hidden">
                <div class="bg-gradient-to-br from-merah-600 to-merah-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                    <div class="relative flex gap-6 items-center">
                        <div class="w-20 h-20 bg-white/20 rounded-xl overflow-hidden flex-shrink-0 shadow-lg">
                            @if($booking->tourPackage?->image)
                                <img src="{{ asset('storage/' . $booking->tourPackage->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white font-black text-xl">🗺️</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-white/60 mb-1 uppercase tracking-widest">Paket Wisata</p>
                            <h2 class="text-xl font-black mb-1">{{ $booking->tourPackage?->name ?? 'Paket Tidak Tersedia' }}</h2>
                            <p class="text-sm text-white/70">
                                {{ $booking->tourPackage?->duration_days ?? '-' }} Hari ·
                                {{ $booking->passenger_count }} Orang
                            </p>
                            <p class="text-xs text-white/50 mt-2 tracking-widest font-bold">{{ $booking->booking_code }}</p>
                        </div>
                        <div>
                            @php
                                $statusClass = match($booking->payment_status) {
                                    'paid'    => 'bg-emerald-400/30 text-emerald-100 border border-emerald-300/30',
                                    'pending' => 'bg-amber-400/30 text-amber-100 border border-amber-300/30',
                                    default   => 'bg-white/20 text-white/80',
                                };
                                $statusLabel = match($booking->payment_status) {
                                    'paid'    => 'Lunas',
                                    'pending' => 'Menunggu Bayar',
                                    default   => ucfirst($booking->payment_status),
                                };
                            @endphp
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>

                    {{-- Destinations --}}
                    @if($booking->tourPackage?->destinations)
                    <div class="mt-6 pt-6 border-t border-white/20 relative">
                        <p class="text-xs text-white/50 uppercase tracking-widest mb-3">Destinasi</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($booking->tourPackage->destinations as $dest)
                            <span class="px-3 py-1 bg-white/15 rounded-full text-xs font-medium">{{ $dest }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Detail Info --}}
                <div class="p-8 space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-warm-50">
                        <span class="text-gray-warm-500 text-sm">Tanggal Keberangkatan</span>
                        <span class="font-semibold text-dark">{{ $booking->travel_date?->translatedFormat('d F Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-warm-50">
                        <span class="text-gray-warm-500 text-sm">Jumlah Peserta</span>
                        <span class="font-semibold text-dark">{{ $booking->passenger_count }} orang</span>
                    </div>
                    @if($booking->tourPackage?->duration_days)
                    <div class="flex justify-between items-center py-3 border-b border-gray-warm-50">
                        <span class="text-gray-warm-500 text-sm">Durasi Wisata</span>
                        <span class="font-semibold text-dark">{{ $booking->tourPackage->duration_days }} hari</span>
                    </div>
                    @endif
                    @if($booking->notes)
                    <div class="flex justify-between items-start py-3 border-b border-gray-warm-50">
                        <span class="text-gray-warm-500 text-sm">Catatan</span>
                        <span class="font-semibold text-dark text-right max-w-xs text-sm italic">{{ $booking->notes }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center py-4 bg-merah-50 rounded-xl px-4 mt-2">
                        <span class="text-gray-warm-500 font-medium">Total Pembayaran</span>
                        <span class="text-2xl font-black text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Proof --}}
            @if($booking->payment_proof)
            <div class="card p-8">
                <h3 class="text-base font-bold text-dark mb-4">Bukti Pembayaran</h3>
                <img src="{{ asset('storage/' . $booking->payment_proof) }}" class="max-w-sm rounded-xl shadow-md border border-gray-warm-100">

                @if($booking->payment_status !== 'paid')
                <form action="{{ route('admin.tour.approve-manual', $booking) }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="btn-primary px-6">✓ Setujui Pembayaran Ini</button>
                </form>
                @endif
            </div>
            @endif
        </div>

        {{-- Right Column: Customer & Info --}}
        <div class="space-y-6">

            {{-- Customer Info --}}
            <div class="card p-6">
                <h3 class="text-base font-bold text-dark mb-4">Informasi Pelanggan</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-merah-100 rounded-full flex items-center justify-center font-bold text-merah-600 text-sm">
                        {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-dark text-sm">{{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-warm-400">{{ $booking->user->email }}</p>
                    </div>
                </div>
                @if($booking->user->phone)
                <p class="text-sm text-gray-warm-500">📞 {{ $booking->user->phone }}</p>
                @endif
            </div>

            {{-- Booking Meta --}}
            <div class="card p-6">
                <h3 class="text-base font-bold text-dark mb-4">Informasi Pesanan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-warm-500">Kode Booking</span>
                        <span class="font-mono font-bold text-merah-600">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-warm-500">Metode Bayar</span>
                        <span class="font-medium text-dark">{{ $booking->payment_method ? ucfirst($booking->payment_method) : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-warm-500">Dibuat</span>
                        <span class="font-medium text-dark">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-warm-500">Status</span>
                        <span class="{{ match($booking->payment_status) { 'paid' => 'badge-success', 'pending' => 'badge-warning', default => 'badge-gray' } }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Payment History --}}
            @if($booking->payments->count() > 0)
            <div class="card p-6">
                <h3 class="text-base font-bold text-dark mb-4">Riwayat Pembayaran</h3>
                <div class="space-y-3">
                    @foreach($booking->payments as $payment)
                    <div class="flex justify-between items-center text-sm border-b border-gray-warm-50 pb-2 last:border-0">
                        <div>
                            <p class="font-medium text-dark">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-warm-400">{{ $payment->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $payment->status === 'success' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
