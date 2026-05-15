@extends('layouts.app')
@section('title', 'Sewa Bus Premium - Bus 88')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
/* ── Leaflet Tailwind Fix ── */
.leaflet-container img.leaflet-tile { max-width: none !important; max-height: none !important; }
.leaflet-container img { max-width: none !important; }

.rental-hero {
    padding-top: 10rem;
    padding-bottom: 22rem;
    background: linear-gradient(rgba(15,2,2,.93),rgba(15,2,2,.93)),
                url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&q=80&w=1600') center/cover fixed;
}
#rental-map {
    height: 400px;
    width: 100%;
    border-radius: 1.5rem;
    z-index: 1;
    border: 2px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.form-card {
    background: #fff;
    border-radius: 2rem;
    box-shadow: 0 25px 70px -15px rgba(0,0,0,.15);
    border: 1px solid rgba(0,0,0,.04);
    padding: 3rem;
}
.side-card {
    border-radius: 2rem;
    box-shadow: 0 15px 45px -10px rgba(0,0,0,.08);
    border: 1px solid rgba(0,0,0,.05);
    padding: 2.5rem;
}
select.input-field {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 1.25rem center;
    background-repeat: no-repeat;
    background-size: 1.2em;
    padding-right: 3rem;
}

/* Hide Spin Buttons */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }

/* Timeline Style for Tutorial */
.step-item {
    position: relative;
    padding-left: 3rem;
}
.step-item::before {
    content: '';
    position: absolute;
    left: 1.15rem;
    top: 2.5rem;
    bottom: -1.5rem;
    width: 2px;
    background: rgba(220, 38, 38, 0.1);
}
.step-item:last-child::before {
    display: none;
}
.step-number {
    position: absolute;
    left: 0;
    top: 0;
    width: 2.3rem;
    height: 2.3rem;
    background: #dc2626;
    color: white;
    border-radius: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    z-index: 2;
}
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen font-inter">

    {{-- Hero --}}
    <section class="rental-hero">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <span class="inline-block px-6 py-2 rounded-full bg-red-900/40 text-red-400 text-[10px] font-black uppercase tracking-[.4em] mb-10 border border-red-800/50">
                Premium Charter Service
            </span>
            <h1 class="text-6xl md:text-8xl font-black text-white mb-6 leading-tight">
                Sewa Bus <span class="text-red-600">Premium</span>
            </h1>
            <p class="text-gray-400 text-xl max-w-2xl mx-auto leading-relaxed">
                Pilihan armada terbaik dengan fasilitas lengkap untuk kenyamanan perjalanan Anda.
            </p>
        </div>
    </section>

    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 md:-mt-40 pb-32 relative z-10">
        <div class="grid lg:grid-cols-12 gap-10 items-start">

            {{-- ── FORM ── --}}
            <div class="lg:col-span-8">
                @if(session('rental_success'))
                <div class="form-card text-center animate-fadeIn">
                    <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-4xl font-black text-gray-900 mb-4">Pengajuan Berhasil!</h2>
                    <p class="text-gray-500 mb-10 max-w-sm mx-auto text-lg">Tim operasional kami akan segera menghubungi Anda melalui WhatsApp.</p>
                    <div class="max-w-md mx-auto bg-gray-50 rounded-3xl p-8 text-left space-y-4 mb-10 border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-center"><span class="text-gray-400 font-black uppercase text-[10px] tracking-widest">KODE BOOKING</span><span class="font-black text-2xl text-red-600">{{ session('rental_success')['code'] }}</span></div>
                        <div class="flex justify-between items-center border-t border-gray-200 pt-4"><span class="text-gray-400 font-black uppercase text-[10px] tracking-widest">TUJUAN</span><span class="font-bold text-gray-900">{{ session('rental_success')['destination'] }}</span></div>
                        <div class="flex justify-between items-center border-t border-gray-200 pt-4"><span class="text-gray-400 font-black uppercase text-[10px] tracking-widest">TANGGAL</span><span class="font-bold text-gray-900">{{ session('rental_success')['start_date'] }}</span></div>
                    </div>
                    <div class="flex gap-4 justify-center">
                        <a href="{{ route('dashboard') }}" class="btn-primary px-10 py-4 font-black">DASHBOARD SAYA</a>
                        <a href="{{ route('rental.index') }}" class="btn-secondary px-10 py-4 font-black text-gray-600">SEWA LAGI</a>
                    </div>
                </div>
                @else
                <div class="form-card relative">
                    {{-- Deco Background --}}
                    <div class="absolute top-0 right-0 w-96 h-96 bg-red-50 rounded-full -mr-48 -mt-48 opacity-40 pointer-events-none"></div>

                    <form action="{{ route('rental.store') }}" method="POST" class="space-y-10 relative z-10">
                        @csrf

                        <div class="flex items-center gap-4">
                            <div class="w-1.5 h-12 bg-red-600 rounded-full"></div>
                            <h2 class="text-3xl font-black text-gray-900">Formulir Sewa Bus</h2>
                        </div>

                        {{-- Bus Selection --}}
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Armada Bus</label>
                            <select name="bus_id" id="bus_selector" class="input-field py-4 font-bold text-gray-800 cursor-pointer w-full bg-gray-50 border-gray-200 focus:bg-white" required>
                                <option value="">— Silahkan Pilih Armada —</option>
                                @foreach($buses as $bus)
                                <option value="{{ $bus->id }}"
                                    data-capacity="{{ $bus->capacity }}"
                                    {{ old('bus_id')==$bus->id?'selected':'' }}>
                                    {{ $bus->name }} ({{ $bus->type }} - {{ $bus->capacity }} Kursi)
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dates --}}
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="input-field py-4 w-full bg-gray-50 border-gray-200 focus:bg-white font-bold" min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}" required>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="input-field py-4 w-full bg-gray-50 border-gray-200 focus:bg-white font-bold" min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}" required>
                            </div>
                        </div>

                        {{-- Pickup Location --}}
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Lokasi Penjemputan</label>
                            <div class="relative">
                                <input type="text" id="pickup_location" name="pickup_location"
                                    class="input-field py-4 pr-16 w-full bg-gray-50 border-gray-200 focus:bg-white font-bold"
                                    placeholder="Ketik alamat lengkap atau pilih di peta..."
                                    value="{{ old('pickup_location') }}" required>
                                <button type="button" id="btn-locate"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                            {{-- MAP --}}
                            <div id="rental-map" class="rounded-3xl overflow-hidden shadow-inner"></div>
                            <p class="text-[11px] text-gray-400 mt-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Klik pada peta untuk menandai titik jemput yang akurat.
                            </p>
                        </div>

                        {{-- Destination + Passengers --}}
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Kota Tujuan</label>
                                <input type="text" name="destination" class="input-field py-4 w-full bg-gray-50 border-gray-200 focus:bg-white font-bold" placeholder="Contoh: Yogyakarta, Bali..." value="{{ old('destination') }}" required>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Jumlah Penumpang</label>
                                <input type="number" name="passenger_count" id="passenger_count" class="input-field py-4 w-full bg-gray-50 border-gray-200 focus:bg-white font-bold" placeholder="Minimal 1 orang" min="1" onkeydown="if(['+', '-', 'e', 'E', '.'].includes(event.key)) event.preventDefault();" value="{{ old('passenger_count') }}" required>
                                <p id="capacity_warning" class="text-[10px] font-bold text-red-500 mt-1 hidden">⚠️ Jumlah penumpang melebihi kapasitas bus!</p>
                            </div>
                        </div>

                        {{-- Purpose --}}
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Keperluan Sewa</label>
                            <textarea name="purpose" rows="3" class="input-field py-4 resize-none w-full bg-gray-50 border-gray-200 focus:bg-white font-bold" placeholder="Misal: Ziarah Walisongo, Study Tour, Gathering Perusahaan, dsb...">{{ old('purpose') }}</textarea>
                        </div>

                        {{-- Contact Info --}}
                        <div class="pt-8 border-t border-gray-100">
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-6">Informasi Kontak Pemesan</p>
                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <input type="text" name="contact_name" class="input-field py-4 w-full bg-gray-50 border-gray-200 focus:bg-white font-bold" value="{{ old('contact_name', auth()->user()->name ?? '') }}" required>
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                                    <input type="text" name="contact_phone" class="input-field py-4 w-full bg-gray-50 border-gray-200 focus:bg-white font-bold" placeholder="08xx-xxxx-xxxx" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" id="submit-btn"
                            class="btn-primary w-full py-6 font-black text-lg flex items-center justify-center gap-4 rounded-3xl shadow-xl hover:scale-[1.01] active:scale-[.98] transition-all">
                            <span id="btn-text uppercase">KONFIRMASI SEWA SEKARANG</span>
                            <svg id="btn-spinner" class="hidden animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- ── SIDEBAR ── --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Tutorial Cara Memesan --}}
                <div class="side-card bg-white border-2 border-gray-100">
                    <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-50">
                        <div class="w-2 h-8 bg-red-600 rounded-full"></div>
                        <h4 class="font-black text-xl text-gray-900 tracking-tight">Cara Memesan</h4>
                    </div>
                    <div class="space-y-10">
                        @foreach([
                            ['Pilih Armada','Pilih bus dari pilihan yang tersedia sesuai dengan jumlah rombongan Anda.'],
                            ['Isi Detail Perjalanan','Tentukan tanggal berangkat, lokasi jemput, dan tujuan perjalanan Anda.'],
                            ['Konfirmasi Admin','Tim kami akan menghubungi Anda untuk detail harga dan ketersediaan bus.'],
                            ['Pembayaran','Lakukan pembayaran DP sesuai instruksi admin untuk mengamankan armada.']
                        ] as $index => $s)
                        <div class="step-item">
                            <div class="step-number">{{ $index + 1 }}</div>
                            <h5 class="font-bold text-gray-900 mb-1 leading-tight">{{ $s[0] }}</h5>
                            <p class="text-xs text-gray-500 leading-relaxed font-medium">{{ $s[1] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- WhatsApp Support --}}
                <div class="side-card bg-emerald-600 text-white text-center relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-all duration-500"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </div>
                        <h4 class="text-2xl font-black mb-2">Butuh Bantuan?</h4>
                        <p class="text-sm text-white/80 mb-8 font-medium">Konsultasi ketersediaan armada langsung bersama admin kami.</p>
                        <a href="https://wa.me/6281121100025" target="_blank"
                            class="block w-full py-4 bg-white text-emerald-700 font-black rounded-2xl hover:bg-gray-50 transition-all shadow-xl hover:scale-105 active:scale-95">
                            CHAT ADMIN SEKARANG
                        </a>
                    </div>
                </div>

                {{-- Important Notice --}}
                <div class="side-card bg-amber-50 border-2 border-amber-100 shadow-none">
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-amber-900 uppercase tracking-widest mb-1">Penting</p>
                            <p class="text-xs text-amber-700 leading-relaxed font-medium">Harga yang tertera merupakan estimasi. Harga final akan dikonfirmasi admin berdasarkan rute spesifik.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Bus Capacity Validation ──
    const busSelector = document.getElementById('bus_selector');
    const paxInput = document.getElementById('passenger_count');
    const warning = document.getElementById('capacity_warning');
    const submitBtn = document.getElementById('submit-btn');

    function validateCapacity() {
        const option = busSelector.options[busSelector.selectedIndex];
        if (!option || !option.value) return;

        const capacity = parseInt(option.dataset.capacity) || 0;
        let pax = parseInt(paxInput.value) || 0;

        // Set the max attribute dynamically
        paxInput.setAttribute('max', capacity);

        if (pax > capacity) {
            // Strictly cap the value
            paxInput.value = capacity;
            pax = capacity;
            
            // Show brief warning that then fades or stays
            warning.textContent = `⚠️ Kapasitas maksimal bus ini adalah ${capacity} kursi.`;
            warning.classList.remove('hidden');
            
            // Optional: hide warning after 3 seconds since we auto-fixed the value
            setTimeout(() => warning.classList.add('hidden'), 3000);
        } else {
            warning.classList.add('hidden');
        }
        
        // Reset button state since we auto-cap the value
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        paxInput.classList.remove('border-red-500', 'bg-red-50');
    }

    busSelector.addEventListener('change', validateCapacity);
    paxInput.addEventListener('input', validateCapacity);

    // ── Leaflet Map Setup ──
    const mapElement = document.getElementById('rental-map');
    if (mapElement) {
        // Tailwind img fix
        const mapStyle = document.createElement('style');
        mapStyle.textContent = '.leaflet-container img { max-width: none !important; max-height: none !important; width: auto !important; }';
        document.head.appendChild(mapStyle);

        const map = L.map('rental-map', { scrollWheelZoom: false }).setView([-7.9839, 112.6214], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19
        }).addTo(map);

        const markerIcon = L.divIcon({
            className: '',
            html: '<div style="width:20px;height:20px;border-radius:50%;background:#dc2626;border:4px solid #fff;box-shadow:0 4px 10px rgba(220,38,38,0.4)"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        let mainMarker = L.marker([-7.9839, 112.6214], { draggable: true, icon: markerIcon }).addTo(map);
        const inputPickup = document.getElementById('pickup_location');
        const inputLat = document.getElementById('latitude');
        const inputLng = document.getElementById('longitude');

        function fetchAddress(lat, lng) {
            inputLat.value = lat;
            inputLng.value = lng;
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) inputPickup.value = data.display_name;
                })
                .catch(err => console.log('Geocoding error:', err));
        }

        map.on('click', e => {
            mainMarker.setLatLng(e.latlng);
            fetchAddress(e.latlng.lat, e.latlng.lng);
        });

        mainMarker.on('dragend', () => {
            const position = mainMarker.getLatLng();
            fetchAddress(position.lat, position.lng);
        });

        document.getElementById('btn-locate').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    const coords = [pos.coords.latitude, pos.coords.longitude];
                    map.flyTo(coords, 16);
                    mainMarker.setLatLng(coords);
                    fetchAddress(coords[0], coords[1]);
                });
            }
        });

        // Ensure map renders correctly
        setTimeout(() => map.invalidateSize(), 800);
    }

    // ── Submit Handling ──
    const rentalForm = document.querySelector('form');
    if (rentalForm) {
        rentalForm.addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            const text = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');
            btn.disabled = true;
            text.textContent = 'MENGIRIM PENGAJUAN...';
            spinner.classList.remove('hidden');
        });
    }
});
</script>
@endpush
