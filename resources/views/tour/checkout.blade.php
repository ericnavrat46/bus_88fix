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
                            <h3 class="text-sm font-bold text-gray-warm-400 uppercase mb-4 tracking-wider">Opsi 1: Otomatis (Midtrans)</h3>
                            <button id="pay-button" class="btn-primary w-full py-4 text-lg font-bold shadow-lg flex items-center justify-center gap-2">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                 BAYAR SEKARANG
                            </button>
                            <p class="text-[10px] text-gray-warm-400 text-center mt-3 italic">Mendukung: VA, E-Wallet (Gopay, ShopeePay), Kartu Kredit</p>
                        </div>

                        <div class="flex items-center gap-4 mb-8">
                            <div class="h-px flex-1 bg-gray-warm-100"></div>
                            <span class="text-[10px] font-bold text-gray-warm-300">ATAU</span>
                            <div class="h-px flex-1 bg-gray-warm-100"></div>
                        </div>

                        {{-- Manual --}}
                        <div>
                            <h3 class="text-sm font-bold text-gray-warm-400 uppercase mb-4 tracking-wider">Opsi 2: Transfer Manual</h3>
                            <div class="p-4 bg-gray-warm-50 rounded-xl mb-4 text-xs">
                                <p class="text-gray-warm-500 mb-2">Transfer ke Rekening BRI:</p>
                                <p class="text-base font-black text-dark">1234-5678-9012-345</p>
                                <p class="text-gray-warm-400">a.n. PT Bus 88 Merah Putih</p>
                            </div>
                            
                            <form action="{{ route('tour.upload-proof', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input type="file" name="payment_proof" class="input-field py-2 text-xs" required accept="image/*">
                                <button type="submit" class="btn-secondary w-full py-3 font-bold text-sm">UNGGAH BUKTI BAYAR</button>
                            </form>
                        </div>
                    @else
                        <div class="p-6 bg-red-50 border border-red-100 rounded-2xl text-center">
                            <p class="text-red-700 font-medium">Sistem pembayaran sedang sibuk. Silakan coba beberapa saat lagi.</p>
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
