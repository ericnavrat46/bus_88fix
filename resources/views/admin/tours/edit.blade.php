@extends('layouts.admin')
@section('title', 'Edit Paket Wisata - Admin')
@section('page-title', 'Edit Paket Wisata')
@section('content')
<div class="max-w-4xl">
    <div class="card p-8">
        <form action="{{ route('admin.tour-packages.update', $package) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="label-field">Nama Paket *</label>
                    <input type="text" name="name" class="input-field" required value="{{ $package->name }}">
                </div>
                <div>
                    <label class="label-field">Durasi (Hari) *</label>
                    <input type="number" name="duration_days" class="input-field" required min="1" value="{{ $package->duration_days }}">
                </div>
            </div>

            <div>
                <label class="label-field">Deskripsi Paket *</label>
                <textarea name="description" class="input-field" rows="6" required>{{ $package->description }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="label-field">Harga per Orang *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-warm-400 font-bold">Rp</span>
                        <input type="number" name="price_per_person" class="input-field pl-12" required value="{{ (int)$package->price_per_person }}">
                    </div>
                </div>
                <div>
                    <label class="label-field">Status *</label>
                    <select name="status" class="input-field" required>
                        <option value="active" {{ $package->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $package->status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="label-field">Ganti Foto Paket</label>
                @if($package->image)
                    <div class="mb-4">
                        <p class="text-xs text-gray-warm-400 mb-2">Foto Saat Ini:</p>
                        <img src="{{ asset('storage/' . $package->image) }}" class="w-48 h-32 object-cover rounded-xl border border-gray-warm-200">
                    </div>
                @endif
                <input type="file" name="image" class="input-field" accept="image/*">
                <p class="text-[10px] text-gray-warm-400 mt-1">Format: JPG, PNG, WEBP (Maks. 2MB). Biarkan kosong jika tidak ingin ganti.</p>
            </div>

            <div class="pt-6 border-t border-gray-warm-100">
                <h3 class="text-sm font-bold text-dark mb-4 uppercase tracking-wider">Detail Tambahan</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="label-field">Destinasi Utama * (Pisahkan dengan koma)</label>
                        <input type="text" name="destinations" class="input-field" required value="{{ is_array($package->destinations) ? implode(', ', $package->destinations) : $package->destinations }}">
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="label-field">Sudah Termasuk (Satu baris per item)</label>
                            <textarea name="inclusions" class="input-field" rows="5">{{ is_array($package->inclusions) ? implode("\n", $package->inclusions) : '' }}</textarea>
                        </div>
                        <div>
                            <label class="label-field">Belum Termasuk (Satu baris per item)</label>
                            <textarea name="exclusions" class="input-field" rows="5">{{ is_array($package->exclusions) ? implode("\n", $package->exclusions) : '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex gap-4">
                <button type="submit" class="btn-primary px-8">UPDATE PAKET</button>
                <a href="{{ route('admin.tour-packages.index') }}" class="btn-secondary px-8">BATAL</a>
            </div>
        </form>
    </div>
</div>
@endsection
