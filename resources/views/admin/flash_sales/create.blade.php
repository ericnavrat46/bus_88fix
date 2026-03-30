@extends('layouts.admin')
@section('title', 'Buat Flash Sale Baru - Admin')
@section('page-title', 'Buat Flash Sale Baru')

@section('content')
<div class="max-w-4xl">
    <div class="card p-8 shadow-sm">
        <form action="{{ route('admin.flash-sales.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="label-field">Judul Promo *</label>
                    <input type="text" name="title" class="input-field" placeholder="Contoh: Promo Ramadhan 50%" required value="{{ old('title') }}">
                </div>

                <div>
                    <label class="label-field">Jenis Produk *</label>
                    <select name="target_type" class="input-field" id="target_type" required>
                        <option value="" disabled selected>Pilih jenis produk</option>
                        <option value="tour_package">Paket Wisata (Tour)</option>
                        <option value="schedule">Jadwal Bus</option>
                    </select>
                </div>

                <div>
                    <label class="label-field">Pilih Item *</label>
                    <select name="target_id" class="input-field" id="target_id" required>
                        <option value="" disabled selected>Pilih item spesifik</option>
                    </select>
                </div>

                <div>
                    <label class="label-field">Jenis Potongan *</label>
                    <select name="discount_type" class="input-field" required>
                        <option value="fixed">Nominal (Rupiah)</option>
                        <option value="percentage">Persentase (%)</option>
                    </select>
                </div>

                <div>
                    <label class="label-field">Nilai Potongan *</label>
                    <div class="relative">
                        <input type="number" name="discount_value" class="input-field" placeholder="Contoh: 10000 atau 15" required value="{{ old('discount_value') }}">
                    </div>
                </div>

                <div>
                    <label class="label-field">Waktu Mulai *</label>
                    <input type="datetime-local" name="start_time" class="input-field" required value="{{ old('start_time') }}">
                </div>

                <div>
                    <label class="label-field">Waktu Berakhir *</label>
                    <input type="datetime-local" name="end_time" class="input-field" required value="{{ old('end_time') }}">
                </div>

                <div>
                    <label class="label-field">Kuota (Orang) *</label>
                    <input type="number" name="quota" class="input-field" placeholder="Contoh: 20" required value="{{ old('quota') }}">
                </div>
            </div>

            <div class="pt-8 flex gap-4">
                <button type="submit" class="btn-primary px-8">SIMPAN FLASH SALE</button>
                <a href="{{ route('admin.flash-sales.index') }}" class="btn-secondary px-8">BATAL</a>
            </div>
        </form>
    </div>
</div>

<script>
    const tourPackages = @json($tourPackages);
    const schedules = @json($schedules);
    const typeSelect = document.getElementById('target_type');
    const targetSelect = document.getElementById('target_id');

    typeSelect.addEventListener('change', function() {
        targetSelect.innerHTML = '<option value="" disabled selected>Pilih item spesifik</option>';
        const type = this.value;
        const options = (type === 'tour_package') ? tourPackages : schedules;

        options.forEach(opt => {
            const el = document.createElement('option');
            el.value = opt.id;
            el.text = (type === 'tour_package') 
                ? opt.name 
                : `${opt.route.origin} → ${opt.route.destination} (${opt.bus.name})`;
            targetSelect.appendChild(el);
        });
    });
</script>
@endsection
