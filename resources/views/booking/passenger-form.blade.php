@extends('layouts.app')
@section('title', 'Data Penumpang - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Steps --}}
        <div class="flex items-center justify-center gap-0 mb-10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                <span class="text-sm font-semibold text-emerald-600">Pilih Kursi</span>
            </div>
            <div class="w-12 h-0.5 bg-merah-600 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-merah-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                <span class="text-sm font-semibold text-merah-600">Data Penumpang</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-warm-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-warm-200 text-gray-warm-500 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                <span class="text-sm font-medium text-gray-warm-400">Pembayaran</span>
            </div>
        </div>

        <div class="card p-8">
            <h2 class="text-xl font-bold text-dark mb-2">Data Penumpang</h2>
            <p class="text-gray-warm-500 mb-8">Lengkapi data untuk {{ count($selectedSeats) }} penumpang</p>

            <form method="POST" action="{{ route('booking.store', $schedule) }}" class="space-y-6">
                @csrf
                @foreach($selectedSeats as $index => $seat)
                <div class="p-6 bg-gray-warm-50 rounded-2xl border border-gray-warm-100">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-merah-600 text-white rounded-xl flex items-center justify-center font-bold text-sm">{{ $seat }}</div>
                        <div>
                            <h3 class="font-bold text-dark">Penumpang Kursi {{ $seat }}</h3>
                            <p class="text-xs text-gray-warm-500">Kursi #{{ $seat }}</p>
                        </div>
                    </div>
                    <input type="hidden" name="passengers[{{ $index }}][seat_number]" value="{{ $seat }}">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="label-field">Nama Lengkap *</label>
                            <input type="text" name="passengers[{{ $index }}][passenger_name]" class="input-field" placeholder="Sesuai KTP" required>
                        </div>
                        <div>
                            <label class="label-field">No. KTP/Identitas</label>
                            <input type="text" name="passengers[{{ $index }}][id_number]" class="input-field" placeholder="Opsional">
                        </div>
                        <div>
                            <label class="label-field">No. Telepon</label>
                            <input type="text" name="passengers[{{ $index }}][phone]" class="input-field" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Summary & Promo --}}
                <div class="p-6 bg-merah-50 rounded-2xl border border-merah-100">
                    <div class="mb-6 pb-6 border-b border-merah-200">
                        <label class="label-field mb-2 block">Kode Promo</label>
                        <div class="flex gap-2">
                            <input type="text" id="promoCodeInput" name="promo_code" value="{{ request('promo') }}" class="input-field uppercase flex-grow" placeholder="Masukkan kode promo">
                            <button type="button" id="applyPromoBtn" class="btn-primary px-6">Gunakan</button>
                        </div>
                        <p id="promoMessage" class="text-sm font-bold mt-2 hidden"></p>
                        <input type="hidden" name="applied_promo_id" id="appliedPromoId">
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-gray-warm-600">
                            <span>Subtotal ({{ count($selectedSeats) }} kursi)</span>
                            <span id="subtotalAmount" data-amount="{{ $schedule->price * count($selectedSeats) }}">Rp {{ number_format($schedule->price * count($selectedSeats), 0, ',', '.') }}</span>
                        </div>
                        <div id="discountRow" class="flex items-center justify-between text-green-600 hidden">
                            <span>Diskon Promo</span>
                            <span id="discountAmount">- Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-merah-200">
                            <span class="text-gray-warm-600 font-bold">Total Pembayaran</span>
                            <span id="totalAmount" class="text-xl font-black text-merah-600">Rp {{ number_format($schedule->price * count($selectedSeats), 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-warm-500 mt-4">{{ $schedule->route->origin }} → {{ $schedule->route->destination }} • {{ $schedule->departure_date->translatedFormat('d M Y') }}</p>
                </div>

                <button type="submit" class="btn-primary w-full text-center text-lg py-4">
                    Lanjut ke Pembayaran
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const applyPromoBtn = document.getElementById('applyPromoBtn');
        const promoCodeInput = document.getElementById('promoCodeInput');
        const promoMessage = document.getElementById('promoMessage');
        const appliedPromoId = document.getElementById('appliedPromoId');
        
        const subtotalAmountSpan = document.getElementById('subtotalAmount');
        const discountRow = document.getElementById('discountRow');
        const discountAmountSpan = document.getElementById('discountAmount');
        const totalAmountSpan = document.getElementById('totalAmount');
        
        const subtotal = parseFloat(subtotalAmountSpan.dataset.amount);

        // Auto-apply if promo is in URL
        if(promoCodeInput.value.trim() !== '') {
            validatePromo(promoCodeInput.value.trim());
        }

        applyPromoBtn.addEventListener('click', function() {
            const code = promoCodeInput.value.trim();
            if(!code) return;
            validatePromo(code);
        });

        function validatePromo(code) {
            applyPromoBtn.disabled = true;
            applyPromoBtn.innerHTML = 'Mengecek...';
            promoMessage.classList.add('hidden');

            fetch('{{ route("promo.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    promo_code: code,
                    target_type: 'ticket',
                    amount: subtotal
                })
            })
            .then(response => response.json())
            .then(data => {
                applyPromoBtn.disabled = false;
                applyPromoBtn.innerHTML = 'Gunakan';
                promoMessage.classList.remove('hidden');

                if(data.valid) {
                    promoMessage.className = 'text-sm font-bold mt-2 text-green-600';
                    promoMessage.textContent = data.message;
                    appliedPromoId.value = data.promo_id;

                    // Update UI
                    discountRow.classList.remove('hidden');
                    discountAmountSpan.textContent = '- Rp ' + new Intl.NumberFormat('id-ID').format(data.discount_amount);
                    totalAmountSpan.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal - data.discount_amount);
                } else {
                    promoMessage.className = 'text-sm font-bold mt-2 text-red-600';
                    promoMessage.textContent = data.message;
                    appliedPromoId.value = '';

                    // Reset UI
                    discountRow.classList.add('hidden');
                    totalAmountSpan.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                applyPromoBtn.disabled = false;
                applyPromoBtn.innerHTML = 'Gunakan';
            });
        }
    });
</script>
@endpush
@endsection
