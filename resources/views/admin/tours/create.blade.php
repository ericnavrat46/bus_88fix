@extends('layouts.admin')
@section('title', 'Tambah Paket Wisata - Admin')
@section('page-title', 'Tambah Paket Wisata')
@section('content')
<div class="max-w-4xl">
    <div class="card p-8">
        <form action="{{ route('admin.tour-packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="label-field">Nama Paket *</label>
                    <input type="text" name="name" class="input-field" required placeholder="Contoh: Pesona Bali & Nusa Penida">
                </div>
                <div>
                    <label class="label-field">Durasi (Hari) *</label>
                    <input type="number" name="duration_days" class="input-field" required min="1" placeholder="Contoh: 4">
                </div>
            </div>

            <div>
                <label class="label-field">Deskripsi Paket *</label>
                <textarea name="description" class="input-field" rows="6" required placeholder="Jelaskan detail perjalanan secara lengkap..."></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="label-field">Harga per Orang *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-warm-400 font-bold">Rp</span>
                        <input type="number" name="price_per_person" class="input-field pl-12" required placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="label-field">Status *</label>
                    <select name="status" class="input-field" required>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="label-field">Foto Paket</label>
                <input type="file" name="image" class="input-field" accept="image/*">
                <p class="text-[10px] text-gray-warm-400 mt-1">Format: JPG, PNG, WEBP (Maks. 2MB)</p>
            </div>

            <div class="pt-6 border-t border-gray-warm-100">
                <h3 class="text-sm font-bold text-dark mb-4 uppercase tracking-wider">Detail Tambahan</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="label-field">Destinasi Utama * (Pisahkan dengan koma)</label>
                        <input type="text" name="destinations" class="input-field" required placeholder="Contoh: Pantai Pandawa, Ubud, Kintamani">
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="label-field">Sudah Termasuk (Satu baris per item)</label>
                            <textarea name="inclusions" class="input-field" rows="5" placeholder="Contoh:&#10;Transportasi AC&#10;Hotel Bintang 4&#10;Makan 3x Sehari"></textarea>
                        </div>
                        <div>
                            <label class="label-field">Belum Termasuk (Satu baris per item)</label>
                            <textarea name="exclusions" class="input-field" rows="5" placeholder="Contoh:&#10;Tiket Pesawat&#10;Pengeluaran Pribadi"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex gap-4">
                <button type="submit" class="btn-primary px-8">SIMPAN PAKET</button>
                <a href="{{ route('admin.tour-packages.index') }}" class="btn-secondary px-8">BATAL</a>
            </div>
        </form>
    </div>
</div>
@endsection
