@extends('layouts.admin')
@section('page-title', isset($schedule) ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('content')
<div class="max-w-2xl">
    <div class="card p-8">
        <form method="POST" action="{{ isset($schedule) ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}" class="space-y-5">
            @csrf
            @if(isset($schedule)) @method('PUT') @endif
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="label-field">Bus *</label>
                    <select name="bus_id" class="select-field" required>
                        <option value="">Pilih Bus</option>
                        @foreach($buses as $bus)<option value="{{ $bus->id }}" {{ (old('bus_id', $schedule->bus_id ?? '') == $bus->id) ? 'selected' : '' }}>{{ $bus->name }} ({{ ucfirst($bus->type) }} - {{ $bus->capacity }} kursi)</option>@endforeach
                    </select>
                </div>
                <div><label class="label-field">Rute *</label>
                    <select name="route_id" class="select-field" required>
                        <option value="">Pilih Rute</option>
                        @foreach($routes as $route)<option value="{{ $route->id }}" {{ (old('route_id', $schedule->route_id ?? '') == $route->id) ? 'selected' : '' }}>{{ $route->origin }} → {{ $route->destination }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <div><label class="label-field">Tanggal *</label><input type="date" name="departure_date" class="input-field" value="{{ old('departure_date', isset($schedule) ? $schedule->departure_date->format('Y-m-d') : '') }}" required></div>
                <div><label class="label-field">Jam Berangkat *</label><input type="time" name="departure_time" class="input-field" value="{{ old('departure_time', $schedule->departure_time ?? '') }}" required></div>
                <div><label class="label-field">Jam Tiba *</label><input type="time" name="arrival_time" class="input-field" value="{{ old('arrival_time', $schedule->arrival_time ?? '') }}" required></div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="label-field">Harga per Kursi *</label><input type="number" name="price" class="input-field" value="{{ old('price', $schedule->price ?? '') }}" required></div>
                <div><label class="label-field">Status *</label>
                    <select name="status" class="select-field" required>
                        <option value="active" {{ (old('status', $schedule->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
                        <option value="cancelled" {{ (old('status', $schedule->status ?? '') === 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                        @if(isset($schedule))<option value="completed" {{ (old('status', $schedule->status ?? '') === 'completed') ? 'selected' : '' }}>Completed</option>@endif
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">{{ isset($schedule) ? 'Simpan' : 'Tambah Jadwal' }}</button>
                <a href="{{ route('admin.schedules.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
