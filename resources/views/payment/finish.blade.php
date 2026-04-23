@extends('layouts.app')
@section('title', 'Status Pembayaran - Bus 88')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="text-center max-w-md">
        @if($status === 'settlement' || $status === 'capture')
            <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse-glow" style="--tw-shadow-color: rgba(16,185,129,0.3);">
                <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-2xl font-black text-dark mb-2">Pembayaran Berhasil! 🎉</h1>
            <p class="text-gray-warm-500 mb-2">Kode Transaksi: <strong>{{ $orderId }}</strong></p>
            <p class="text-gray-warm-500 mb-8">E-tiket telah tersedia di dashboard Anda.</p>
        @elseif($status === 'pending')
            <div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-2xl font-black text-dark mb-2">Menunggu Pembayaran</h1>
            <p class="text-gray-warm-500 mb-2">Kode: <strong>{{ $orderId }}</strong></p>
            <p class="text-gray-warm-500 mb-8">Silakan selesaikan pembayaran Anda.</p>
        @else
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h1 class="text-2xl font-black text-dark mb-2">Pembayaran Gagal</h1>
            <p class="text-gray-warm-500 mb-8">Silakan coba lagi atau hubungi customer service.</p>
        @endif
        <div class="flex flex-wrap gap-3 justify-center">
            @if(isset($redirectUrl))
                <a href="{{ $redirectUrl }}" class="btn-primary">Lihat Detail Pesanan</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn-primary">Ke Dashboard</a>
            @endif
            <a href="{{ route('home') }}" class="btn-secondary">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(isset($payment) && $status === 'pending')
        // 1. WebSocket Listener (Reverb)
        window.Echo.channel('payment.{{ $payment->id }}')
            .listen('.payment.updated', (e) => {
                if (e.status === 'settlement' || e.status === 'capture') {
                    window.location.reload();
                }
            });

        // 2. Backup Polling (Sangat berguna untuk pengujian di Localhost)
        let checkCount = 0;
        const maxChecks = 40; // Cek selama 2 menit (40 * 3 detik)

        const statusInterval = setInterval(() => {
            checkCount++;
            if (checkCount > maxChecks) {
                clearInterval(statusInterval);
                return;
            }

            fetch('{{ route("payment.check-status", $payment->id) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status && (data.payment_status === 'settlement' || data.payment_status === 'capture' || data.payment_status === 'paid')) {
                        clearInterval(statusInterval);
                        window.location.reload();
                    }
                })
                .catch(err => console.error('Error checking status:', err));
        }, 3000); // Cek setiap 3 detik
    @endif
</script>
@endpush
