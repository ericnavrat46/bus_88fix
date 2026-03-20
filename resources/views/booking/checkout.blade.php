@extends('layouts.app')
@section('title', 'Checkout - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Steps --}}
        <div class="flex items-center justify-center gap-0 mb-10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                <span class="text-sm font-semibold text-emerald-600">Pilih Kursi</span>
            </div>
            <div class="w-12 h-0.5 bg-emerald-500 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                <span class="text-sm font-semibold text-emerald-600">Data Penumpang</span>
            </div>
            <div class="w-12 h-0.5 bg-merah-600 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-merah-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                <span class="text-sm font-semibold text-merah-600">Pembayaran</span>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            {{-- Booking Details --}}
            <div>
                <div class="card p-8">
                    @if($booking->payment_status === 'pending' && $booking->expired_at)
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-amber-800 font-medium">
                            Selesaikan pembayaran sebelum <span class="font-bold underline">{{ $booking->expired_at->translatedFormat('H:i') }} WIB</span> agar tidak otomatis dibatalkan.
                        </p>
                    </div>
                    @endif
                    <h2 class="text-xl font-bold text-dark mb-6">Detail Booking</h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-warm-500">Kode Booking</span>
                            <span class="font-bold text-dark tracking-wider">{{ $booking->booking_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-warm-500">Rute</span>
                            <span class="font-semibold text-dark">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-warm-500">Tanggal</span>
                            <span class="font-semibold text-dark">{{ $booking->schedule->departure_date->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-warm-500">Jam</span>
                            <span class="font-semibold text-dark">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-warm-500">Bus</span>
                            <span class="font-semibold text-dark">{{ $booking->schedule->bus->name }}</span>
                        </div>
                    </div>

                    <h3 class="font-bold text-dark mb-3">Penumpang</h3>
                    <div class="space-y-2">
                        @foreach($booking->passengers as $p)
                        <div class="flex items-center gap-3 p-3 bg-gray-warm-50 rounded-xl">
                            <div class="w-8 h-8 bg-merah-100 rounded-lg flex items-center justify-center text-xs font-bold text-merah-600">{{ $p->seat_number }}</div>
                            <span class="text-sm font-medium text-dark">{{ $p->passenger_name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div>
                <div class="card p-8">
                    <h2 class="text-xl font-bold text-dark mb-6">Pembayaran</h2>

                    <div class="p-6 bg-merah-50 rounded-2xl border border-merah-100 mb-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-warm-500 mb-1">Total Pembayaran</p>
                            <p class="text-3xl font-black text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-warm-400 mt-2">{{ $booking->total_seats }} kursi × Rp {{ number_format($booking->schedule->price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($snapToken)
                        {{-- Midtrans Option --}}
                        <div class="mb-8">
                            <h3 class="font-bold text-dark mb-3">Metode 1: Pembayaran Instan</h3>
                            <button id="pay-button" class="btn-primary w-full text-center text-lg py-4 animate-pulse-glow">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Bayar via Midtrans
                            </button>
                            <p class="text-xs text-gray-warm-400 text-center mt-3">Virtual Account, E-Wallet, Kartu Kredit</p>
                        </div>

                        {{-- Manual Option --}}
                        <div class="pt-6 border-t border-gray-warm-100">
                            <h3 class="font-bold text-dark mb-3">Metode 2: Transfer Manual</h3>
                            <div class="p-4 bg-gray-warm-50 rounded-xl mb-4 text-sm">
                                <p class="text-gray-warm-600 mb-2">Silakan transfer ke rekening berikut:</p>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-dark">BANK BRI</span>
                                    <span class="text-merah-600 font-mono">1234-5678-9012-345</span>
                                </div>
                                <p class="text-xs text-gray-warm-500">a.n. PT Bus 88 Merah Putih</p>
                            </div>

                            <form action="{{ route('booking.upload-proof', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="label-field text-xs">Unggah Bukti Pembayaran</label>
                                    <input type="file" name="payment_proof" class="input-field py-2 text-sm" required accept="image/*">
                                    <p class="text-[10px] text-gray-warm-400 mt-1">*Format: JPG, PNG, JPEG. Maks 2MB</p>
                                </div>
                                <button type="submit" class="btn-secondary w-full py-3 text-sm">
                                    Unggah Bukti & Konfirmasi
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center p-6 bg-amber-50 rounded-2xl border border-amber-200">
                            <p class="text-amber-700 font-medium">Snap token tidak tersedia. Silakan coba lagi.</p>
                            <a href="{{ route('dashboard') }}" class="btn-secondary btn-sm mt-4">Ke Dashboard</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($snapToken)
@push('scripts')
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function() {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route("payment.finish") }}?order_id={{ $booking->booking_code }}&transaction_status=settlement';
            },
            onPending: function(result) {
                window.location.href = '{{ route("payment.finish") }}?order_id={{ $booking->booking_code }}&transaction_status=pending';
            },
            onError: function(result) {
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                // User closed popup without finishing
            }
        });
    });
</script>
@endpush
@endif
@endsection
