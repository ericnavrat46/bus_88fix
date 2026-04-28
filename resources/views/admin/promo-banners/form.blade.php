@extends('layouts.admin')
@section('title', isset($banner) ? 'Edit Banner Promo' : 'Tambah Banner Promo')
@section('content')
<div class="max-w-4xl">
    <div class="mb-8">
        <a href="{{ route('admin.promo-banners.index') }}" class="text-sm font-bold text-merah-600 flex items-center gap-2 mb-2 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-black text-dark">{{ isset($banner) ? 'Edit Banner' : 'Tambah Banner Baru' }}</h1>
    </div>

    <div class="card p-8">
        <form action="{{ isset($banner) ? route('admin.promo-banners.update', $banner) : route('admin.promo-banners.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-8"
              id="bannerForm">
            @csrf
            @if(isset($banner)) @method('PUT') @endif

            {{-- Image Upload --}}
            <div>
                <label class="label-field block mb-4">Upload Gambar Banner <span class="text-red-500">*</span></label>
                <div class="flex flex-col gap-6">
                    {{-- Preview Container --}}
                    <div id="imagePreviewContainer" class="relative group aspect-[21/9] w-full max-w-2xl bg-gray-warm-100 rounded-3xl overflow-hidden border-2 border-dashed border-gray-warm-200 flex items-center justify-center transition-all hover:border-merah-200">
                        @if(isset($banner))
                            <img src="{{ $banner->image_url }}" id="imagePreview" class="w-full h-full object-cover">
                        @else
                            <div id="placeholder" class="text-center p-8">
                                <svg class="w-12 h-12 text-gray-warm-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm font-bold text-gray-warm-400">Pilih gambar landscape (16:9 atau 21:9)</p>
                                <p class="text-[10px] text-gray-warm-400 mt-1 uppercase tracking-widest">Max 2MB • JPG, PNG, WEBP</p>
                            </div>
                            <img id="imagePreview" class="hidden w-full h-full object-cover">
                        @endif
                        
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                            <label for="imageInput" class="btn-white btn-sm cursor-pointer">Ganti Gambar</label>
                        </div>
                    </div>
                    
                    <input type="file" name="image" id="imageInput" class="hidden" accept="image/*" {{ isset($banner) ? '' : 'required' }}>
                    <p id="imageError" class="text-xs text-red-500 font-bold hidden"></p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="col-span-2">
                    <label class="label-field">Judul Promo <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" class="input-field" maxlength="100" required placeholder="Contoh: Disney Adventure - Lion King Celebration">
                </div>

                {{-- Promo Code --}}
                <div>
                    <label class="label-field">Kode Promo <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="promo_code" id="promoCodeInput" value="{{ old('promo_code', $banner->promo_code ?? '') }}" class="input-field uppercase font-black tracking-widest" required placeholder="CONTOH: DISNEY2026">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5 text-gray-warm-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                    </div>
                    <p class="mt-1 text-[10px] text-gray-warm-400 font-bold uppercase tracking-tight">Hanya huruf dan angka. Otomatis huruf besar.</p>
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="label-field">Urutan Tampilan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="input-field" min="0" required>
                </div>

                {{-- Dates --}}
                <div>
                    <label class="label-field">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" id="startDate" value="{{ old('start_date', isset($banner) ? $banner->start_date->format('Y-m-d') : '') }}" class="input-field" required>
                </div>
                <div>
                    <label class="label-field">Tanggal Berakhir <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" id="endDate" value="{{ old('end_date', isset($banner) ? $banner->end_date->format('Y-m-d') : '') }}" class="input-field" required>
                </div>

                {{-- Link --}}
                <div class="col-span-2">
                    <label class="label-field">Link Tujuan (Opsional)</label>
                    <input type="url" name="link" value="{{ old('link', $banner->link ?? '') }}" class="input-field" placeholder="https://example.com/promo-page">
                </div>

                {{-- Description --}}
                <div class="col-span-2">
                    <label class="label-field">Deskripsi Singkat (Opsional)</label>
                    <textarea name="description" class="input-field min-h-[100px]" maxlength="200" placeholder="Berikan info tambahan menarik untuk banner ini...">{{ old('description', $banner->description ?? '') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <p class="text-[10px] text-gray-warm-400 font-bold uppercase tracking-tight">Maksimal 200 karakter.</p>
                        <p id="charCount" class="text-[10px] text-gray-warm-400 font-bold">0/200</p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-warm-200 rounded-full peer-checked:bg-merah-600 transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6"></div>
                        </div>
                        <span class="text-sm font-black text-dark group-hover:text-merah-600 transition-colors">Banner Aktif & Tampil di Homepage</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-4 pt-8 border-t border-gray-warm-100">
                <button type="submit" class="btn-primary px-10 py-3 shadow-xl shadow-merah-600/20" id="submitBtn">
                    {{ isset($banner) ? 'Simpan Perubahan' : 'Terbitkan Banner' }}
                </button>
                <a href="{{ route('admin.promo-banners.index') }}" class="btn-secondary px-8 py-3">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('placeholder');
        const imageError = document.getElementById('imageError');
        const promoCodeInput = document.getElementById('promoCodeInput');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const charCount = document.getElementById('charCount');
        const description = document.querySelector('textarea[name="description"]');

        // Image Preview & Validation
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Size validation
                if (file.size > 2 * 1024 * 1024) {
                    showImageError('Ukuran gambar maksimal 2MB.');
                    imageInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = new Image();
                    img.onload = function() {
                        const ratio = img.width / img.height;
                        // Ratio validation (accept between 1.5 and 2.5 for landscape)
                        if (ratio < 1.5) {
                            showImageError('Gambar harus landscape (rekomendasi 16:9 atau 21:9).');
                            imageInput.value = '';
                            return;
                        }

                        hideImageError();
                        imagePreview.src = event.target.result;
                        imagePreview.classList.remove('hidden');
                        if(placeholder) placeholder.classList.add('hidden');
                    };
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        function showImageError(msg) {
            imageError.textContent = msg;
            imageError.classList.remove('hidden');
            imagePreview.classList.add('hidden');
            if(placeholder) placeholder.classList.remove('hidden');
        }

        function hideImageError() {
            imageError.classList.add('hidden');
        }

        // Auto Uppercase Promo Code
        promoCodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });

        // Date Validation
        endDate.addEventListener('change', function() {
            if (startDate.value && this.value < startDate.value) {
                Swal.fire({
                    title: 'Kesalahan Tanggal',
                    text: 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
                    icon: 'error',
                    confirmButtonColor: '#cc0000'
                });
                this.value = '';
            }
        });

        // Char Count
        description.addEventListener('input', function() {
            charCount.textContent = `${this.value.length}/200`;
        });
        
        // Initial char count
        charCount.textContent = `${description.value.length}/200`;
    });
</script>
@endpush
@endsection
