@extends('layouts.admin')
@section('title', 'Edit Flash Sale - Admin')
@section('page-title', 'Edit Flash Sale')

@section('content')
<div class="max-w-4xl">
    <div class="card p-8 shadow-sm">
        <form action="{{ route('admin.flash-sales.update', $flashSale) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl">
                    <ul class="list-disc list-inside text-sm font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="label-field">Judul Promo *</label>
                    <input type="text" name="title" class="input-field" required value="{{ old('title', $flashSale->title) }}">
                </div>

                <div>
                    <label class="label-field">Jenis Produk *</label>
                    <select name="target_type" class="input-field" id="target_type" required>
                        <option value="" disabled>Pilih jenis produk</option>
                        <option value="tour_package" {{ $flashSale->target_type === 'tour_package' ? 'selected' : '' }}>Paket Wisata (Tour)</option>
                        <option value="schedule" {{ $flashSale->target_type === 'schedule' ? 'selected' : '' }}>Jadwal Bus</option>
                    </select>
                </div>

                <div>
                    <label class="label-field">Pilih Item *</label>
                    <select name="target_id" class="input-field" id="target_id" required>
                        <option value="" disabled>Pilih item spesifik</option>
                    </select>
                </div>

                <div>
                    <label class="label-field">Jenis Potongan *</label>
                    <select name="discount_type" class="input-field" required>
                        <option value="fixed" {{ $flashSale->discount_type === 'fixed' ? 'selected' : '' }}>Nominal (Rupiah)</option>
                        <option value="percentage" {{ $flashSale->discount_type === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                    </select>
                </div>

                <div>
                    <label class="label-field">Nilai Potongan *</label>
                    <input type="number" name="discount_value" class="input-field" required value="{{ old('discount_value', $flashSale->discount_value) }}">
                </div>

                <div>
                    <label class="label-field">Waktu Mulai *</label>
                    <input type="datetime-local" name="start_time" class="input-field" required value="{{ old('start_time', $flashSale->start_time->format('Y-m-d\TH:i')) }}">
                </div>

                <div>
                    <label class="label-field">Waktu Berakhir *</label>
                    <input type="datetime-local" name="end_time" class="input-field" required value="{{ old('end_time', $flashSale->end_time->format('Y-m-d\TH:i')) }}">
                </div>

                <div>
                    <label class="label-field">Kuota (Orang) *</label>
                    <input type="number" name="quota" class="input-field" required value="{{ old('quota', $flashSale->quota) }}">
                </div>

            </div>

            <div class="pt-8 flex gap-4">
                <button type="submit" class="btn-primary px-8">UPDATE FLASH SALE</button>
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
    const currentTargetId = {{ $flashSale->target_id }};

    function populateTargets(type) {
        targetSelect.innerHTML = '<option value="" disabled>Pilih item spesifik</option>';
        const options = (type === 'tour_package') ? tourPackages : schedules;

        options.forEach(opt => {
            const el = document.createElement('option');
            el.value = opt.id;
            el.text = (type === 'tour_package') 
                ? opt.name 
                : `${opt.route.origin} → ${opt.route.destination} (${opt.bus.name})`;
            if (opt.id === currentTargetId) el.selected = true;
            targetSelect.appendChild(el);
        });
    }

    // Auto-populate on load
    if (typeSelect.value) {
        populateTargets(typeSelect.value);
    }

    typeSelect.addEventListener('change', function() {
        populateTargets(this.value);
    });
</script>
@endsection
