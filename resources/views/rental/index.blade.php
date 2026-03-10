@extends('layouts.app')
@section('title', 'Sewa Bus - Bus 88')
@push('styles')
<style>
    .rental-form-card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .rental-form-card:hover {
        box-shadow: 0 24px 50px rgba(204,0,0,0.1), 0 8px 20px rgba(0,0,0,0.07);
        transform: translateY(-3px);
    }
    .info-card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .info-card:hover {
        box-shadow: 0 16px 36px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .info-item {
        transition: background 0.2s ease, transform 0.2s ease;
        border-radius: 0.75rem;
        padding: 0.5rem;
        margin: -0.5rem;
    }
    .info-item:hover {
        background: rgba(204,0,0,0.04);
        transform: translateX(4px);
    }
    .btn-submit {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(204,0,0,0.3);
    }
    .btn-submit:active {
        transform: translateY(0);
    }
</style>
@endpush
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    {{-- Hero --}}
    <section class="gradient-merah py-16 relative overflow-hidden">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="relative max-w-4xl mx-auto px-4 text-center text-white">
            <h1 class="text-3xl lg:text-5xl font-black mb-4">Sewa Bus Charter</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">Sewa bus untuk wisata, rombongan, atau acara khusus. Hubungi kami dan dapatkan penawaran terbaik!</p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Form --}}
            <div class="lg:col-span-2">
                <div class="card p-8 rental-form-card">
                    <h2 class="text-xl font-bold text-dark mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Form Permintaan Sewa
                    </h2>
                    <form method="POST" action="{{ route('rental.store') }}" class="space-y-5">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="label-field">Tanggal Mulai *</label>
                                <input type="date" name="start_date" class="input-field" min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}" required>
                            </div>
                            <div>
                                <label class="label-field">Tanggal Selesai *</label>
                                <input type="date" name="end_date" class="input-field" min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}" required>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="label-field">Lokasi Penjemputan *</label>
                                <input type="text" name="pickup_location" class="input-field" placeholder="Contoh: Hotel Grand, Jakarta" value="{{ old('pickup_location') }}" required>
                            </div>
                            <div>
                                <label class="label-field">Tujuan *</label>
                                <input type="text" name="destination" class="input-field" placeholder="Contoh: Bandung" value="{{ old('destination') }}" required>
                            </div>
                        </div>
                        <div>
                            <label class="label-field">Pilih Bus (opsional)</label>
                            <select name="bus_id" class="select-field">
                                <option value="">Biarkan kami yang pilihkan</option>
                                @foreach($buses as $bus)
                                <option value="{{ $bus->id }}">{{ $bus->name }} - {{ ucfirst($bus->type) }} ({{ $bus->capacity }} kursi)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="label-field">Jumlah Penumpang</label>
                                <input type="number" name="passenger_count" class="input-field" placeholder="Perkiraan" value="{{ old('passenger_count') }}" min="1">
                            </div>
                            <div>
                                <label class="label-field">Tujuan/Keperluan</label>
                                <input type="text" name="purpose" class="input-field" placeholder="Wisata, Kantor, dll" value="{{ old('purpose') }}">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="label-field">Nama Kontak *</label>
                                <input type="text" name="contact_name" class="input-field" placeholder="Nama lengkap" value="{{ old('contact_name', auth()->user()->name ?? '') }}" required>
                            </div>
                            <div>
                                <label class="label-field">No. Telepon Kontak *</label>
                                <input type="text" name="contact_phone" class="input-field" placeholder="08xxxxxxxxxx" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary w-full text-center btn-submit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Permintaan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info --}}
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-24 info-card">
                    <h3 class="text-lg font-bold text-dark mb-4">Informasi Sewa</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3 info-item">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-gray-warm-600">Pengajuan akan direview oleh admin dalam 1×24 jam</p>
                        </div>
                        <div class="flex items-start gap-3 info-item">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-gray-warm-600">Harga disepakati setelah verifikasi</p>
                        </div>
                        <div class="flex items-start gap-3 info-item">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-gray-warm-600">Pembayaran aman via Midtrans</p>
                        </div>
                        <div class="flex items-start gap-3 info-item">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-gray-warm-600">Armada terawat & supir berpengalaman</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
