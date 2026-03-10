@extends('layouts.app')
@section('title', 'Booking ' . $package->name . ' - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-dark mb-2">Konfirmasi Pesanan</h1>
            <p class="text-gray-warm-500">Lengkapi data perjalanan Anda untuk {{ $package->name }}</p>
        </div>

        <div class="grid lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3">
                <div class="card p-8">
                    <form action="{{ route('tour.store-booking', $package->slug) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="label-field">Tanggal Keberangkatan *</label>
                            <input type="date" name="travel_date" class="input-field" required min="{{ date('Y-m-d', strtotime('+3 days')) }}">
                            <p class="text-[10px] text-gray-warm-400 mt-1">*Pemesanan minimal 3 hari sebelum keberangkatan</p>
                        </div>

                        <div>
                            <label class="label-field">Jumlah Peserta (Orang) *</label>
                            <input type="number" name="passenger_count" class="input-field" required min="1" placeholder="Masukkan jumlah orang" x-model="pax" x-init="pax = 1">
                        </div>

                        <div>
                            <label class="label-field">Alamat Penjemputan / Catatan Tambahan</label>
                            <textarea name="notes" class="input-field" rows="4" placeholder="Misal: Jemput di Hotel Aston Seminyak, atau permintaan khusus lainnya..."></textarea>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="btn-primary w-full py-4 text-lg font-bold">LANJUT KE PEMBAYARAN</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="card p-6 bg-white sticky top-24">
                    <h3 class="font-bold text-dark mb-4">Ringkasan Paket</h3>
                    <div class="flex gap-4 mb-6">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-warm-100 flex-shrink-0">
                            @if($package->image)
                                <img src="{{ asset('storage/' . $package->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-merah-600 flex items-center justify-center text-white font-bold">88</div>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-dark text-sm leading-tight">{{ $package->name }}</p>
                            <p class="text-xs text-gray-warm-500 mt-1">{{ $package->duration_days }} Hari</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-6 border-t border-gray-warm-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-warm-500">Harga per Orang</span>
                            <span class="font-semibold">Rp {{ number_format($package->price_per_person, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-black pt-4">
                            <span class="text-dark">Total</span>
                            <span class="text-merah-600" id="display-total">Rp {{ number_format($package->price_per_person, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100 italic text-[10px] text-blue-700">
                        *Harga sudah termasuk semua pajak dan biaya layanan yang berlaku.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple total update
    const paxInput = document.getElementsByName('passenger_count')[0];
    const displayTotal = document.getElementById('display-total');
    const basePrice = {{ $package->price_per_person }};

    paxInput.addEventListener('input', function() {
        const total = basePrice * (parseInt(this.value) || 0);
        displayTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>
@endsection
