@extends('layouts.admin')
@section('page-title', isset($route) ? 'Edit Rute' : 'Tambah Rute')
@section('content')
<div class="max-w-2xl">
    <div class="card p-8">
        <form method="POST" action="{{ isset($route) ? route('admin.routes.update', $route) : route('admin.routes.store') }}" class="space-y-5">
            @csrf
            @if(isset($route)) @method('PUT') @endif
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="label-field">Kota Asal *</label><input type="text" name="origin" class="input-field" value="{{ old('origin', $route->origin ?? '') }}" required></div>
                <div><label class="label-field">Kota Tujuan *</label><input type="text" name="destination" class="input-field" value="{{ old('destination', $route->destination ?? '') }}" required></div>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <div><label class="label-field">Jarak (km)</label><input type="number" name="distance" class="input-field" value="{{ old('distance', $route->distance ?? '') }}"></div>
                <div><label class="label-field">Durasi (menit) *</label><input type="number" name="duration" class="input-field" value="{{ old('duration', $route->duration ?? '') }}" required></div>
                <div><label class="label-field">Harga Dasar *</label><input type="number" name="base_price" class="input-field" value="{{ old('base_price', $route->base_price ?? '') }}" required></div>
            </div>
            <div><label class="label-field">Status *</label><select name="status" class="select-field" required><option value="active" {{ (old('status', $route->status ?? '') === 'active') ? 'selected' : '' }}>Active</option><option value="inactive" {{ (old('status', $route->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option></select></div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">{{ isset($route) ? 'Simpan' : 'Tambah Rute' }}</button>
                <a href="{{ route('admin.routes.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
