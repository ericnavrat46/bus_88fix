@extends('layouts.app')
@section('title', 'Pembayaran Sewa - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="card p-8 text-center">
            <h2 class="text-2xl font-bold text-dark mb-6">Pembayaran Sewa Bus</h2>
            <div class="p-6 bg-merah-50 rounded-2xl border border-merah-100 mb-6">
                <p class="text-sm text-gray-warm-500 mb-1">Total Pembayaran</p>
                <p class="text-3xl font-black text-merah-600">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-warm-400 mt-2">{{ $rental->rental_code }} • {{ $rental->destination }}</p>
            </div>
            @if($snapToken)
                {{-- Midtrans Option --}}
                <div class="mb-8 p-6 bg-white rounded-xl border border-gray-warm-100 shadow-sm">
                    <h3 class="font-bold text-dark mb-3">Opsi 1: Pembayaran Instan</h3>
                    <button id="pay-button" class="btn-primary w-full text-center text-lg py-4 animate-pulse-glow">
                        Bayar via Midtrans
                    </button>
                    <p class="text-xs text-gray-warm-400 mt-3 text-center">Virtual Account, E-Wallet, Kartu Kredit</p>
                </div>

                <div class="flex items-center gap-4 mb-8">
                    <div class="h-px flex-1 bg-gray-warm-200"></div>
                    <span class="text-xs font-bold text-gray-warm-400 uppercase tracking-widest">ATAU</span>
                    <div class="h-px flex-1 bg-gray-warm-200"></div>
                </div>
            @endif

            {{-- Transfer Manual — selalu tampil --}}
            <div class="p-6 bg-white rounded-xl border border-gray-warm-100 shadow-sm text-left">
                <h3 class="font-bold text-dark mb-4 text-center">{{ $snapToken ? 'Opsi 2: Transfer Manual' : 'Transfer Manual' }}</h3>
                <div class="p-4 bg-gray-warm-50 rounded-xl mb-6 text-sm">
                    <p class="text-gray-warm-600 mb-2">Silakan transfer ke rekening berikut:</p>
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-dark">BANK BRI</span>
                        <span class="text-merah-600 font-mono text-base">1234-5678-9012-345</span>
                    </div>
                    <p class="text-xs text-gray-warm-500">a.n. PT Bus 88 Merah Putih</p>
                </div>

                <form action="{{ route('rental.upload-proof', $rental) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label-field text-xs">Unggah Bukti Pembayaran</label>
                        <input type="file" name="payment_proof" class="input-field py-2.5 text-sm" required accept="image/*">
                        <p class="text-[10px] text-gray-warm-400 mt-1">*Format: JPG, PNG, JPEG. Maks 2MB</p>
                    </div>
                    <button type="submit" class="btn-secondary w-full py-4 font-bold tracking-wide">
                        KLIK UNTUK UNGGAH BUKTI
                    </button>
                </form>
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
        onSuccess: function(result) { window.location.href = '{{ route("payment.finish") }}?order_id={{ $rental->rental_code }}&transaction_status=settlement'; },
        onPending: function(result) { window.location.href = '{{ route("payment.finish") }}?order_id={{ $rental->rental_code }}&transaction_status=pending'; },
        onError: function(result) { alert('Pembayaran gagal.'); }
    });
});
</script>
@endpush
@endif
@endsection
