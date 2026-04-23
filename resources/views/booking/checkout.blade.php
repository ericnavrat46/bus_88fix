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

                    <div class="space-y-6">
                        @if($snapToken)
                            {{-- Midtrans Option --}}
                            <div class="p-6 bg-white rounded-xl border border-emerald-100 shadow-sm text-center">
                                <h3 class="font-bold text-dark mb-3">Opsi 1: Pembayaran Instan</h3>
                                <button id="pay-button" class="btn-primary w-full text-center text-lg py-4 animate-pulse-glow">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Bayar via Midtrans
                                </button>
                                <p class="text-[10px] text-emerald-600 mt-3 italic font-medium">Otomatis Terverifikasi • VA, E-Wallet, Kartu Kredit</p>
                            </div>

                            <div class="flex items-center gap-4 py-2">
                                <div class="h-px flex-1 bg-gray-warm-200"></div>
                                <span class="text-[10px] font-bold text-gray-warm-400 uppercase tracking-[0.2em]">ATAU</span>
                                <div class="h-px flex-1 bg-gray-warm-200"></div>
                            </div>
                        @else
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <p class="text-xs text-amber-700 font-medium text-center italic">Pembayaran instan (Midtrans) sedang kendala. Silakan gunakan Transfer Manual di bawah.</p>
                            </div>
                        @endif

                        {{-- Manual Option --}}
                        <div class="p-6 bg-gray-warm-50 rounded-2xl border border-gray-warm-100">
                            <h3 class="font-bold text-dark mb-4 text-center">Opsi 2: Transfer Manual</h3>
                            <div class="p-4 bg-white rounded-xl border border-gray-warm-200 text-sm mb-4">
                                <p class="text-gray-warm-500 mb-1 text-center font-medium">Bank BRI</p>
                                <p class="text-xl font-black text-merah-600 text-center tracking-wider">1234-5678-9012-345</p>
                                <p class="text-[10px] text-gray-warm-400 text-center uppercase mt-1">a.n. PT Bus 88 Merah Putih</p>
                            </div>
                            
                            <form action="{{ route('booking.upload-proof', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="label-field text-xs">Unggah Bukti Transfer</label>
                                    <input type="file" name="payment_proof" class="input-field py-2.5 text-xs bg-white" required accept="image/*">
                                </div>
                                <button type="submit" class="btn-secondary w-full py-3.5 font-bold uppercase tracking-widest text-[11px]">Unggah Bukti & Konfirmasi</button>
                            </form>
                            <p class="text-[9px] text-gray-warm-400 text-center mt-3">*Verifikasi manual oleh admin (max 1x24 jam)</p>
                        </div>
                    </div>
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
                // Biarkan user tetap di halaman ini untuk menunggu via Echo
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran Anda di aplikasi/bank terkait.',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    timer: 2000
                });
            },
            onError: function(result) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pembayaran gagal. Silakan coba lagi.' });
            }
        });
    });

    // Real-time listener dengan Echo
    @php $payment = $booking->payments->last(); @endphp
    @if($payment)
        window.Echo.channel('payment.{{ $payment->id }}')
            .listen('.payment.updated', (e) => {
                if (e.status === 'settlement' || e.status === 'capture') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Pesanan Anda sedang kami proses.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = '{{ route("payment.finish") }}?order_id={{ $booking->booking_code }}&transaction_status=settlement';
                    });
                }
            });
    @endif
</script>
@endpush
@endif
@endsection