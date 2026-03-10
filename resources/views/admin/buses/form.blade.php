@extends('layouts.admin')
@section('page-title', isset($bus) ? 'Edit Bus' : 'Tambah Bus')
@section('content')
<div class="max-w-2xl">
    <div class="card p-8">
        <form method="POST" action="{{ isset($bus) ? route('admin.buses.update', $bus) : route('admin.buses.store') }}" class="space-y-5">
            @csrf
            @if(isset($bus)) @method('PUT') @endif
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="label-field">Nama Bus *</label><input type="text" name="name" class="input-field" value="{{ old('name', $bus->name ?? '') }}" required></div>
                <div><label class="label-field">Kode *</label><input type="text" name="code" class="input-field" value="{{ old('code', $bus->code ?? '') }}" maxlength="10" required></div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="label-field">Tipe *</label><select name="type" class="select-field" required><option value="ekonomi" {{ (old('type', $bus->type ?? '') === 'ekonomi') ? 'selected' : '' }}>Ekonomi</option><option value="eksekutif" {{ (old('type', $bus->type ?? '') === 'eksekutif') ? 'selected' : '' }}>Eksekutif</option></select></div>
                <div><label class="label-field">Kapasitas *</label><input type="number" name="capacity" class="input-field" value="{{ old('capacity', $bus->capacity ?? 40) }}" min="10" max="60" required></div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="label-field">Plat Nomor *</label><input type="text" name="plate_number" class="input-field" value="{{ old('plate_number', $bus->plate_number ?? '') }}" required></div>
                <div><label class="label-field">Status *</label><select name="status" class="select-field" required><option value="active" {{ (old('status', $bus->status ?? '') === 'active') ? 'selected' : '' }}>Active</option><option value="maintenance" {{ (old('status', $bus->status ?? '') === 'maintenance') ? 'selected' : '' }}>Maintenance</option><option value="inactive" {{ (old('status', $bus->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option></select></div>
            </div>
            <div><label class="label-field">Fasilitas</label><input type="text" name="facilities" class="input-field" value="{{ old('facilities', $bus->facilities ?? '') }}" placeholder="AC,WiFi,USB Charger (pisah dengan koma)"></div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">{{ isset($bus) ? 'Simpan Perubahan' : 'Tambah Bus' }}</button>
                <a href="{{ route('admin.buses.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
