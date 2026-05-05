@extends('layouts.app')
@section('title', 'Checkout Paket - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-8">
            {{-- Details --}}
            <div>
                <div class="card p-8 bg-white border-2 border-dashed border-gray-warm-200">
                    <h2 class="text-xl font-bold text-dark mb-6">Detail Pesanan</h2>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between"><span class="text-gray-warm-500">Kode</span><span class="font-bold text-dark">{{ $booking->booking_code }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-warm-500">Paket</span><span class="font-bold text-dark">{{ $booking->tourPackage->name }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-warm-500">Tanggal</span><span class="font-bold text-dark">{{ $booking->travel_date->format('d F Y') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-warm-500">Jumlah Orang</span><span class="font-bold text-dark">{{ $booking->passenger_count }} Orang</span></div>
                    </div>
                    
                    <div class="mt-8 p-6 bg-merah-50 rounded-2xl border border-merah-100 text-center">
                        <p class="text-xs text-merah-600 font-bold uppercase tracking-widest mb-1">Total Pembayaran</p>
                        <p class="text-3xl font-black text-merah-700">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment Options --}}
            <div>
                <div class="card p-8">
                    <h2 class="text-xl font-bold text-dark mb-6">Pilih Metode Pembayaran</h2>

                    @if($snapToken)
                        {{-- Midtrans --}}
                        <div class="mb-8">
                            <h3 class="text-sm font-bold text-gray-warm-400 uppercase mb-4 tracking-wider text-center">Pembayaran Instan</h3>
                            <button id="pay-button" class="btn-primary w-full py-4 text-lg font-bold shadow-lg flex items-center justify-center gap-2">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                 BAYAR SEKARANG
                            </button>
                            <p class="text-xs text-emerald-600 text-center mt-4 italic font-medium">Virtual Account, E-Wallet, Kartu Kredit • Otomatis Terverifikasi</p>
                        </div>
                    @else
                        <div class="p-8 bg-amber-50 border border-amber-200 rounded-2xl text-center">
                            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="font-bold text-amber-900 mb-2">Sistem Pembayaran Sedang Kendala</h3>
                            <p class="text-sm text-amber-800">Maaf, sistem pembayaran otomatis kami sedang mengalami gangguan teknis. Mohon coba beberapa saat lagi atau hubungi layanan pelanggan kami.</p>
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
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Selesaikan pembayaran Anda untuk melanjutkan.',
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            onError: function(result) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pembayaran gagal.' });
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
                        text: 'Terima kasih, paket wisata Anda telah terkonfirmasi.',
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
