@extends('layouts.admin')
@section('page-title', isset($schedule) ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-8">
        <h2 class="text-2xl font-black text-dark tracking-tight">{{ isset($schedule) ? 'Edit Jadwal Perjalanan' : 'Buat Jadwal Baru' }}</h2>
        <p class="text-gray-warm-500 mt-1">Isi detail di bawah ini untuk mengelola jadwal keberangkatan bus.</p>
    </div>

    <form method="POST" action="{{ isset($schedule) ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}" class="space-y-6">
        @csrf
        @if(isset($schedule)) @method('PUT') @endif

        {{-- Main Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-warm-100 overflow-hidden">
            <div class="bg-gray-warm-50 px-6 py-4 border-b border-gray-warm-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-merah-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-merah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <h3 class="font-bold text-dark">Informasi Rute & Bus</h3>
            </div>
            <div class="p-6 grid md:grid-cols-2 gap-6">
                {{-- Bus Select --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-warm-700">Bus <span class="text-merah-600">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <select name="bus_id" class="w-full pl-10 pr-4 py-3 bg-gray-warm-50 border border-gray-warm-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-600 focus:border-merah-600 transition-all font-medium text-dark" required>
                            <option value="">Pilih Bus</option>
                            @foreach($buses as $bus)
                                <option value="{{ $bus->id }}" {{ (old('bus_id', $schedule->bus_id ?? '') == $bus->id) ? 'selected' : '' }}>
                                    {{ $bus->name }} ({{ ucfirst($bus->type) }} - {{ $bus->capacity }} kursi)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Route Select --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-warm-700">Rute <span class="text-merah-600">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                        <select name="route_id" class="w-full pl-10 pr-4 py-3 bg-gray-warm-50 border border-gray-warm-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-600 focus:border-merah-600 transition-all font-medium text-dark" required>
                            <option value="">Pilih Rute</option>
                            @foreach($routes as $route)
                                <option value="{{ $route->id }}" {{ (old('route_id', $schedule->route_id ?? '') == $route->id) ? 'selected' : '' }}>
                                    {{ $route->origin }} &rarr; {{ $route->destination }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Time & Schedule Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-warm-100 overflow-hidden">
            <div class="bg-gray-warm-50 px-6 py-4 border-b border-gray-warm-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-bold text-dark">Waktu Perjalanan</h3>
            </div>
            <div class="p-6 grid md:grid-cols-3 gap-6">
                {{-- Date --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-warm-700">Tanggal <span class="text-merah-600">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" name="departure_date" class="w-full pl-10 pr-4 py-3 bg-gray-warm-50 border border-gray-warm-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-600 focus:border-merah-600 transition-all font-medium text-dark" value="{{ old('departure_date', isset($schedule) ? $schedule->departure_date->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

                {{-- Departure Time --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-warm-700">Jam Berangkat <span class="text-merah-600">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <input type="time" name="departure_time" class="w-full pl-10 pr-4 py-3 bg-gray-warm-50 border border-gray-warm-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-600 focus:border-merah-600 transition-all font-medium text-dark" value="{{ old('departure_time', $schedule->departure_time ?? '') }}" required>
                    </div>
                </div>

                {{-- Arrival Time --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-warm-700">Jam Tiba <span class="text-merah-600">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <input type="time" name="arrival_time" class="w-full pl-10 pr-4 py-3 bg-gray-warm-50 border border-gray-warm-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-600 focus:border-merah-600 transition-all font-medium text-dark" value="{{ old('arrival_time', $schedule->arrival_time ?? '') }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pricing & Status Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-warm-100 overflow-hidden">
            <div class="bg-gray-warm-50 px-6 py-4 border-b border-gray-warm-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-bold text-dark">Harga & Status</h3>
            </div>
            <div class="p-6 grid md:grid-cols-2 gap-6">
                {{-- Price --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-warm-700">Harga per Kursi <span class="text-merah-600">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-sm font-bold text-gray-warm-500">Rp</span>
                        </div>
                        <input type="number" name="price" class="w-full pl-12 pr-4 py-3 bg-gray-warm-50 border border-gray-warm-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-600 focus:border-merah-600 transition-all font-bold text-dark" value="{{ old('price', $schedule->price ?? '') }}" placeholder="0" required>
                    </div>
                </div>

                {{-- Status --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-warm-700">Status <span class="text-merah-600">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <select name="status" class="w-full pl-10 pr-4 py-3 bg-gray-warm-50 border border-gray-warm-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-600 focus:border-merah-600 transition-all font-medium text-dark" required>
                            <option value="active" {{ (old('status', $schedule->status ?? '') === 'active') ? 'selected' : '' }}>🟢 Active (Berjalan)</option>
                            <option value="cancelled" {{ (old('status', $schedule->status ?? '') === 'cancelled') ? 'selected' : '' }}>🔴 Cancelled (Dibatalkan)</option>
                            @if(isset($schedule))
                                <option value="completed" {{ (old('status', $schedule->status ?? '') === 'completed') ? 'selected' : '' }}>🔵 Completed (Selesai)</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-merah-600 to-merah-700 hover:from-merah-700 hover:to-merah-800 text-white font-bold rounded-xl shadow-lg shadow-merah-600/30 hover:shadow-xl hover:shadow-merah-600/40 transition-all active:scale-[0.98]">
                {{ isset($schedule) ? 'Simpan Perubahan' : 'Tambah Jadwal Baru' }}
            </button>
            <a href="{{ route('admin.schedules.index') }}" class="px-6 py-3.5 bg-white border border-gray-warm-200 text-gray-warm-700 hover:bg-gray-warm-50 hover:text-dark font-bold rounded-xl transition-all active:scale-[0.98]">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
