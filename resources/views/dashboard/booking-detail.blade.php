@extends('layouts.app')
@section('title', 'Detail Booking - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-merah-600 hover:underline mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Dashboard
        </a>

        <div class="card p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-dark">E-Tiket</h1>
                @php
                    $statusClass = match($booking->payment_status) {
                        'paid' => 'badge-success',
                        'pending' => 'badge-warning',
                        'expired' => 'badge-gray',
                        'cancelled' => 'badge-danger',
                        'refunded' => 'badge-info',
                        default => 'badge-gray',
                    };
                    $statusLabel = match($booking->payment_status) {
                        'paid' => 'Lunas',
                        'pending' => 'Menunggu Bayar',
                        'expired' => 'Kedaluwarsa',
                        'cancelled' => 'Dibatalkan',
                        'refunded' => 'Refund',
                        default => $booking->payment_status,
                    };
                @endphp
                <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            {{-- Ticket Card --}}
            <div class="bg-gradient-to-br from-merah-600 to-merah-800 rounded-2xl p-8 text-white mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center"><span class="font-black">88</span></div>
                        <span class="font-bold text-lg">Bus 88</span>
                    </div>
                    <div class="flex items-center gap-6 mb-6">
                        <div>
                            <p class="text-3xl font-black">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</p>
                            <p class="text-sm text-white/70">{{ $booking->schedule->route->origin }}</p>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                            <div class="flex-1 border-t border-dashed border-white/30"></div>
                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-black">{{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }}</p>
                            <p class="text-sm text-white/70">{{ $booking->schedule->route->destination }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div><p class="text-white/50 text-xs">Tanggal</p><p class="font-semibold">{{ $booking->schedule->departure_date->translatedFormat('d M Y') }}</p></div>
                        <div><p class="text-white/50 text-xs">Bus</p><p class="font-semibold">{{ $booking->schedule->bus->name }}</p></div>
                        <div><p class="text-white/50 text-xs">Kode</p><p class="font-semibold tracking-wider">{{ $booking->booking_code }}</p></div>
                    </div>
                </div>
            </div>

            {{-- Passengers --}}
            <h3 class="font-bold text-dark mb-3">Daftar Penumpang</h3>
            <div class="space-y-2 mb-6">
                @foreach($booking->passengers as $p)
                <div class="flex items-center gap-3 p-4 bg-gray-warm-50 rounded-xl">
                    <div class="w-10 h-10 bg-merah-100 rounded-lg flex items-center justify-center text-sm font-bold text-merah-600">{{ $p->seat_number }}</div>
                    <div>
                        <p class="font-semibold text-dark">{{ $p->passenger_name }}</p>
                        <p class="text-xs text-gray-warm-500">Kursi #{{ $p->seat_number }} {{ $p->id_number ? '· ' . $p->id_number : '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <hr class="border-gray-warm-100 mb-6">
            <div class="flex items-center justify-between mb-8">
                <span class="text-gray-warm-500">Total</span>
                <span class="text-2xl font-black text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>

            @if($booking->payment_status === 'paid' && $booking->schedule->departure_date->isPast())
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    @if($booking->reviews()->where('user_id', auth()->id())->exists())
                        <div class="p-4 bg-emerald-50 rounded-xl text-center border border-emerald-100">
                            <p class="text-emerald-700 text-sm font-medium">✨ Terima kasih! Anda sudah memberikan ulasan untuk perjalanan ini.</p>
                        </div>
                    @else
                        <button onclick="openReviewModal('booking', {{ $booking->id }})" class="btn-primary w-full py-4 font-bold flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            BERI ULASAN TRIP
                        </button>
                    @endif
                </div>
            @endif

            {{-- Payment Section --}}
            @if($booking->payment_status === 'pending' && !$booking->isExpired())
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    @if($booking->payment_proof)
                        <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h4 class="font-bold text-emerald-800 mb-1">Bukti Sudah Diunggah</h4>
                            <p class="text-sm text-emerald-600 mb-4">Mohon tunggu verifikasi admin.</p>
                            <img src="{{ asset('storage/' . $booking->payment_proof) }}" class="max-w-xs mx-auto rounded-xl shadow-sm border border-emerald-200">
                            
                            <div class="mt-6">
                                <p class="text-xs text-emerald-500 mb-2 font-medium italic">Ganti bukti pembayaran?</p>
                                <form action="{{ route('booking.upload-proof', $booking) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 max-w-xs mx-auto">
                                    @csrf
                                    <input type="file" name="payment_proof" class="text-xs file:btn-secondary file:btn-xs" required accept="image/*">
                                    <button type="submit" class="p-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100">
                            <h4 class="font-bold text-dark mb-4 text-center">Belum Bayar? Unggah Bukti Transfer</h4>
                            <div class="grid md:grid-cols-2 gap-6 items-center">
                                <div class="p-4 bg-white rounded-xl border border-amber-100 text-sm">
                                    <p class="text-gray-warm-500 mb-2">Transfer ke Rekening BRI:</p>
                                    <p class="text-lg font-black text-merah-600 tracking-wider">1234-5678-9012-345</p>
                                    <p class="text-xs text-gray-warm-400">a.n. PT Bus 88 Merah Putih</p>
                                </div>
                                <form action="{{ route('booking.upload-proof', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <input type="file" name="payment_proof" class="input-field py-2 text-xs" required accept="image/*">
                                    <button type="submit" class="btn-primary w-full py-3 text-sm font-bold">UNGGAH BUKTI SEKARANG</button>
                                </form>
                            </div>
                        </div>
                    @endif
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
            @elseif($booking->payment_status === 'cancelled')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-red-50 rounded-2xl border border-red-100 text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h4 class="font-bold text-red-800 mb-1">Pesanan Dibatalkan</h4>
                        <p class="text-sm text-red-600">Pesanan ini telah dibatalkan.</p>
                    </div>
                </div>
            @elseif($booking->payment_status === 'expired')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-bold text-gray-700 mb-1">Pesanan Kedaluwarsa</h4>
                        <p class="text-sm text-gray-500">Batas waktu pembayaran telah habis.</p>
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="mt-8 pt-8 border-t border-gray-warm-100 flex flex-col sm:flex-row gap-4">
                <a href="https://wa.me/6285784898590?text=Halo CS Bus 88, saya ingin bertanya tentang pesanan tiket {{ $booking->booking_code }} saya." target="_blank" class="btn-secondary flex-1 py-3 font-bold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                    </svg>
                    HUBUNGI CS
                </a>

                @if($booking->payment_status === 'pending' || $booking->payment_status === 'unpaid')
                <form action="{{ route('dashboard.booking.cancel', $booking) }}" method="POST" class="flex-1" onsubmit="confirmCancel(event, this, 'Batalkan Tiket?', 'Apakah Anda yakin ingin membatalkan pesanan tiket ini?');">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl font-bold transition-colors">
                        BATALKAN PESANAN
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('partials.review-modal')
@endpush
