@extends('layouts.app')
@section('title', 'Booking ' . $package->name . ' - Bus 88')
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map {
        height: 250px;
        width: 100%;
        border-radius: 0.75rem;
        margin-top: 0.75rem;
        border: 1px solid #e2e8f0;
        z-index: 1;
    }
    .map-hint {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
</style>
@endpush
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

                        <div class="space-y-4">
                            <label class="label-field">Lokasi Penjemputan / Titik Kumpul *</label>
                            <div class="flex gap-2">
                                <input type="text" id="pickup_location" name="notes" class="input-field" placeholder="Ketik alamat atau pilih di peta" required>
                                <button type="button" id="btn-locate" class="p-2 bg-merah-50 text-merah-600 rounded-lg hover:bg-merah-100 transition-colors" title="Gunakan Lokasi Saya">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                            </div>
                            <div id="map"></div>
                            <div class="map-hint">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pilih lokasi penjemputan Anda di peta
                            </div>
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Simple total update
    const paxInput = document.getElementsByName('passenger_count')[0];
    const displayTotal = document.getElementById('display-total');
    const basePrice = {{ $package->price_per_person }};

    paxInput.addEventListener('input', function() {
        const total = basePrice * (parseInt(this.value) || 0);
        displayTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    });

    // Map Logic
    document.addEventListener('DOMContentLoaded', function() {
        const defaultLat = -8.6500; // Bali default for tours
        const defaultLng = 115.2167;
        
        const map = L.map('map').setView([defaultLat, defaultLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);
        const inputField = document.getElementById('pickup_location');

        function updateAddress(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(r => r.json())
                .then(data => { if (data.display_name) inputField.value = data.display_name; });
        }

        map.locate({setView: true, maxZoom: 16, enableHighAccuracy: true});
        map.on('locationfound', function(e) {
            marker.setLatLng(e.latlng);
            updateAddress(e.latlng.lat, e.latlng.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateAddress(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function(e) {
            const { lat, lng } = marker.getLatLng();
            updateAddress(lat, lng);
        });

        document.getElementById('btn-locate').addEventListener('click', function() {
            map.locate({setView: true, maxZoom: 17, enableHighAccuracy: true});
        });

        setTimeout(() => { map.invalidateSize(); }, 500);
    });
</script>
@endsection
