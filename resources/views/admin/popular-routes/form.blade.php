@extends('layouts.admin')
@section('title', isset($popularRoute) ? 'Edit Rute Populer' : 'Tambah Rute Populer')
@section('content')
<div class="mb-8">
    <a href="{{ route('admin.popular-routes.index') }}" class="text-sm text-gray-warm-500 hover:text-dark flex items-center gap-2 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar
    </a>
    <h1 class="text-2xl font-black text-dark">{{ isset($popularRoute) ? 'Edit Rute Populer' : 'Tambah Rute Populer' }}</h1>
</div>

<div class="max-w-4xl">
    <form action="{{ isset($popularRoute) ? route('admin.popular-routes.update', $popularRoute) : route('admin.popular-routes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @if(isset($popularRoute)) @method('PUT') @endif

        <div class="card p-8 space-y-6">
            {{-- Route Selection --}}
            <div>
                <label class="label-field uppercase tracking-widest text-[10px] font-black opacity-60">Pilih Rute Asal - Tujuan</label>
                <select name="route_id" id="route_id" class="input-field" required>
                    <option value="">Pilih Rute...</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}" 
                                data-price="{{ (int)$route->base_price }}"
                                {{ (old('route_id', $popularRoute->route_id ?? '') == $route->id) ? 'selected' : '' }}>
                            {{ $route->origin }} - {{ $route->destination }} (Rp {{ number_format($route->base_price, 0) }})
                        </option>
                    @endforeach
                </select>
                @error('route_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Image Upload --}}
            <div>
                <label class="label-field uppercase tracking-widest text-[10px] font-black opacity-60">Gambar Rute</label>
                @if(isset($popularRoute) && $popularRoute->image)
                    <div class="mb-4 w-64 rounded-2xl overflow-hidden border border-gray-warm-200">
                        <img src="{{ asset('storage/popular_routes/' . $popularRoute->image) }}" class="w-full h-auto" alt="">
                    </div>
                @endif
                <input type="file" name="image" class="input-field py-2" {{ isset($popularRoute) ? '' : 'required' }} accept="image/*">
                <p class="text-[10px] text-gray-warm-400 mt-2 italic">Format: JPG, PNG, WebP. Maksimal 2MB. Disarankan aspek rasio 4:3 atau 16:9.</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Price Display --}}
                <div>
                    <label class="label-field uppercase tracking-widest text-[10px] font-black opacity-60">Harga Tampilan (Opsional)</label>
                    <input type="text" name="price_display" value="{{ old('price_display', $popularRoute->price_display ?? '') }}" class="input-field" placeholder="Contoh: Rp 150k">
                    <p class="text-[10px] text-gray-warm-400 mt-1 italic">Kosongkan untuk menggunakan harga asli rute.</p>
                </div>

                {{-- Duration Display --}}
                <div>
                    <label class="label-field uppercase tracking-widest text-[10px] font-black opacity-60">Durasi Tampilan (Opsional)</label>
                    <input type="text" name="duration_display" value="{{ old('duration_display', $popularRoute->duration_display ?? '') }}" class="input-field" placeholder="Contoh: 2,5 Jam">
                    <p class="text-[10px] text-gray-warm-400 mt-1 italic">Kosongkan untuk menggunakan durasi asli rute.</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Class Display --}}
                <div>
                    <label class="label-field uppercase tracking-widest text-[10px] font-black opacity-60">Kelas Tampilan (Opsional)</label>
                    <select name="class_display" class="input-field">
                        <option value="">Pilih Kelas...</option>
                        <option value="Eksekutif" {{ old('class_display', $popularRoute->class_display ?? '') == 'Eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                        <option value="Ekonomi" {{ old('class_display', $popularRoute->class_display ?? '') == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                    </select>
                </div>

                {{-- Badge Text --}}
                <div>
                    <label class="label-field uppercase tracking-widest text-[10px] font-black opacity-60">Teks Badge (Opsional)</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text', $popularRoute->badge_text ?? '') }}" class="input-field" placeholder="Contoh: PENAWARAN MENARIK">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Sort Order --}}
                <div>
                    <label class="label-field uppercase tracking-widest text-[10px] font-black opacity-60">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $popularRoute->sort_order ?? 0) }}" class="input-field" required min="0">
                </div>

                {{-- Is Active --}}
                <div class="flex items-center gap-3 pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $popularRoute->is_active ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-bold text-gray-warm-700">Aktifkan Rute Ini</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.popular-routes.index') }}" class="btn-secondary py-3 px-8">Batal</a>
            <button type="submit" class="btn-primary py-3 px-12 shadow-lg shadow-merah-600/20">
                {{ isset($popularRoute) ? 'Perbarui Rute' : 'Simpan Rute' }}
            </button>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('route_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const priceInput = document.getElementsByName('price_display')[0];
        
        if (selectedOption.value && !priceInput.value) {
            const price = selectedOption.getAttribute('data-price');
            if (price) {
                priceInput.value = price;
            }
        }
    });
</script>
@endpush
