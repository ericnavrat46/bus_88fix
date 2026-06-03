@extends('layouts.app')
@section('title', 'Detail Paket Wisata - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-merah-600 hover:underline mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Dashboard
        </a>

        <div class="card p-8">
            @if($booking->payment_status === 'pending')
            <div class="mb-8 p-6 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-4 shadow-sm animate-pulse-subtle">
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-amber-900 leading-tight">Batas Waktu Pembayaran</h4>
                    <p class="text-sm text-amber-800">Paket wisata ini akan dibatalkan otomatis jika tidak dibayar sebelum <span class="font-black underline">{{ $booking->created_at->addHours(2)->translatedFormat('H:i') }} WIB</span> hari ini.</p>
                </div>
            </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-dark">Detail Paket Wisata</h1>
                @php
                    $statusClass = match($booking->payment_status) {
                        'paid'    => 'badge-success',
                        'pending' => 'badge-warning',
                        default   => 'badge-gray',
                    };
                    $statusLabel = match($booking->payment_status) {
                        'paid'    => 'Telah Terbayar',
                        'pending' => 'Menunggu Bayar',
                        default   => ucfirst($booking->payment_status),
                    };
                @endphp
                <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            {{-- Hero Card Paket --}}
            <div class="bg-gradient-to-br from-merah-600 to-merah-800 rounded-2xl p-8 text-white mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                <div class="relative flex gap-6 items-center">
                    {{-- Package Image --}}
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
                </div>

                {{-- Destinations --}}
                @if($booking->tourPackage?->destinations)
                <div class="mt-6 pt-6 border-t border-white/20">
                    <p class="text-xs text-white/50 uppercase tracking-widest mb-3">Destinasi</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($booking->tourPackage->destinations as $dest)
                        <span class="px-3 py-1 bg-white/15 rounded-full text-xs font-medium">{{ $dest }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Info Detail --}}
            <div class="space-y-3 mb-6">
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
            </div>

            {{-- Total Harga --}}
            <div class="flex items-center justify-between mb-8 p-4 bg-merah-50 rounded-xl">
                <span class="text-gray-warm-500">Total Pembayaran</span>
                <span class="text-2xl font-black text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>

            {{-- Payment Section --}}
            @if($booking->payment_status === 'pending')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                        <div class="space-y-6">
                            <a href="{{ route('tour.checkout', $booking) }}" class="btn-primary w-full text-center py-4 text-lg font-bold shadow-lg block">BAYAR SEKARANG</a>
                        </div>
                </div>

            @elseif($booking->payment_status === 'refunded')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        </div>
                        <h4 class="font-bold text-blue-800 mb-1">Tiket Telah Direfund</h4>
                        <p class="text-sm text-blue-600">Dana pembayaran Anda sedang diproses untuk dikembalikan. Hubungi admin jika ada pertanyaan.</p>
                    </div>
                </div>
            @elseif($booking->payment_status === 'paid' || $booking->payment_status === 'pending_refund')
                @php 
                    $refund = \App\Models\Refund::where('tour_booking_id', $booking->id)->first();
                    $departure = \Carbon\Carbon::parse($booking->travel_date->format('Y-m-d') . ' 00:00:00');
                    $canRefund = now()->diffInHours($departure, false) >= 6;
                @endphp
                
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 text-center mb-6">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h4 class="font-bold text-emerald-800 mb-1">Telah Terbayar</h4>
                        <p class="text-sm text-emerald-600">Terima kasih! Pembayaran Anda telah dikonfirmasi.</p>
                    </div>

                    @if($booking->payment_status === 'pending_refund' || $refund)
                        <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-amber-800">Menunggu Verifikasi Refund</h4>
                                    <p class="text-xs text-amber-600 italic">Pesanan tidak dapat digunakan selama proses pemeriksaan.</p>
                                </div>
                            </div>
                            @if($refund && $refund->status !== 'pending')
                            <div class="p-4 bg-white/50 rounded-xl text-sm text-amber-700">
                                <p class="font-bold mb-1">Status Verifikasi: {{ ucfirst($refund->status) }}</p>
                                <p class="italic text-xs">{{ $refund->admin_notes ?? 'Sedang ditinjau oleh Admin...' }}</p>
                            </div>
                            @endif
                        </div>
                    @else
                        @if($canRefund)
                            <div class="p-6 bg-gray-warm-50 rounded-2xl border border-gray-warm-100">
                                <h4 class="font-bold text-dark mb-2">Punya Rencana Lain?</h4>
                                <p class="text-sm text-gray-warm-500 mb-4">Anda dapat mengajukan refund dengan aturan berikut:</p>
                                <ul class="text-[11px] text-gray-400 space-y-1 mb-6 list-disc list-inside">
                                    <li><strong>> 24 jam</strong> sebelum keberangkatan: <strong>Refund 90%</strong></li>
                                    <li><strong>6 - 24 jam</strong> sebelum keberangkatan: <strong>Refund 70%</strong></li>
                                    <li>Kurang dari 6 jam: Refund tidak tersedia.</li>
                                    <li>Dana akan dikembalikan ke rekening Anda (dipotong biaya admin bank jika ada).</li>
                                </ul>
                                <a href="{{ route('tour.refund', $booking) }}" class="btn-secondary w-full py-3 text-sm font-bold text-center block">
                                    AJUKAN REFUND
                                </a>
                            </div>
                        @else
                            <div class="p-4 bg-gray-100 rounded-xl text-center">
                                <p class="text-xs text-gray-400 italic font-medium">Batas waktu refund habis (maksimal 6 jam sebelum berangkat).</p>
                            </div>
                        @endif
                    @endif
                </div>

            @else
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-bold text-gray-700 mb-1">Status: {{ ucfirst($booking->payment_status) }}</h4>
                        <p class="text-sm text-gray-500">Hubungi admin jika ada pertanyaan terkait pesanan ini.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
