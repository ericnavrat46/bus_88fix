@extends('layouts.app')
@section('title', 'Pembayaran Sewa - Bus 88')
@section('content')
    <div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
        <div class="max-w-2xl mx-auto px-4 py-12">
            <div class="card p-8 text-center">
                <h2 class="text-2xl font-bold text-dark mb-6">Pembayaran Sewa Bus</h2>
                <div class="p-6 bg-merah-50 rounded-2xl border border-merah-100 mb-6">
                    <p class="text-sm text-gray-warm-500 mb-1">Total Pembayaran</p>
                    <p class="text-3xl font-black text-merah-600" id="total-price-display">Rp {{ number_format($rental->total_price - ($rental->discount_amount ?? 0), 0, ',', '.') }}
                    </p>
                    @if($rental->discount_amount > 0)
                        <p class="text-xs text-green-600 font-bold mt-1" id="discount-display">Diskon: - Rp {{ number_format($rental->discount_amount, 0, ',', '.') }}</p>
                    @endif
                    <p class="text-xs text-gray-warm-400 mt-2">{{ $rental->rental_code }} • {{ $rental->destination }}</p>
                </div>

                {{-- Promo Code Section --}}
                @if(!$rental->promo_banner_id)
                <div class="mb-8 p-6 bg-white rounded-xl border border-gray-warm-100 shadow-sm text-left">
                    <label class="label-field text-xs mb-2 block">Punya Kode Promo?</label>
                    <div class="flex gap-2">
                        <input type="text" id="promo-code-input" class="input-field py-2.5 text-sm uppercase flex-1" placeholder="MASUKKAN KODE">
                        <button type="button" id="apply-promo-btn" class="btn-primary px-4 text-xs font-bold">GUNAKAN</button>
                    </div>
                    <p id="promo-message" class="text-[10px] font-bold mt-2 hidden"></p>
                </div>
                @endif
                @if($snapToken)
                    {{-- Midtrans Option --}}
                    <div class="mb-8 p-6 bg-white rounded-xl border border-gray-warm-100 shadow-sm">
                        <h3 class="font-bold text-dark mb-3 text-lg">Pembayaran Instan</h3>
                        <button id="pay-button" class="btn-primary w-full text-center text-lg py-4 animate-pulse-glow">
                            Bayar via Midtrans
                        </button>
                        <p class="text-xs text-emerald-600 mt-4 italic font-medium">Virtual Account, E-Wallet, Kartu Kredit • Otomatis Terverifikasi</p>
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
    @if($snapToken)
        @push('scripts')
            <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
            <script>
                document.getElementById('pay-button').addEventListener('click', function () {
                    window.snap.pay('{{ $snapToken }}', {
                        onSuccess: function (result) {
                            window.location.href = '{{ route("payment.finish") }}?order_id={{ $rental->rental_code }}&transaction_status=settlement';
                        },
                        onPending: function (result) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Menunggu Pembayaran',
                                text: 'Silakan selesaikan pembayaran Anda.',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        },
                        onError: function (result) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pembayaran gagal.' });
                        }
                    });
                });

                // Promo Application Logic
                const applyPromoBtn = document.getElementById('apply-promo-btn');
                if (applyPromoBtn) {
                    applyPromoBtn.addEventListener('click', function() {
                        const promoCode = document.getElementById('promo-code-input').value;
                        const promoMessage = document.getElementById('promo-message');
                        
                        if (!promoCode) return;

                        applyPromoBtn.disabled = true;
                        applyPromoBtn.innerText = '...';

                        fetch('{{ route("rental.apply-promo", $rental) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ promo_code: promoCode })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.valid) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Promo Berhasil!',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload(); // Reload to regenerate snap token and update UI
                                });
                            } else {
                                applyPromoBtn.disabled = false;
                                applyPromoBtn.innerText = 'GUNAKAN';
                                promoMessage.innerText = data.message;
                                promoMessage.classList.remove('hidden');
                                promoMessage.classList.add('text-red-500');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            applyPromoBtn.disabled = false;
                            applyPromoBtn.innerText = 'GUNAKAN';
                        });
                    });
                }

                // Real-time listener dengan Echo
                @php $payment = $rental->payments->last(); @endphp
                @if($payment)
                    window.Echo.channel('payment.{{ $payment->id }}')
                        .listen('.payment.updated', (e) => {
                            if (e.status === 'settlement' || e.status === 'capture') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Terima kasih, pembayaran sewa bus Anda telah kami terima.',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    window.location.href = '{{ route("payment.finish") }}?order_id={{ $rental->rental_code }}&transaction_status=settlement';
                                });
                            }
                        });
                @endif
            </script>
        @endpush
    @endif
@endsection