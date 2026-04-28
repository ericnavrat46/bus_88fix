@extends('layouts.admin')
@section('title', 'Kelola Banner Promo')
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-black text-dark">Banner Promo</h1>
        <p class="text-gray-warm-500 text-sm">Kelola carousel banner untuk halaman utama</p>
    </div>
    <a href="{{ route('admin.promo-banners.create') }}" class="btn-primary btn-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Banner Baru
    </a>
</div>

{{-- Filters --}}
<div class="card p-6 mb-8">
    <form action="{{ route('admin.promo-banners.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="label-field text-xs uppercase tracking-widest opacity-60">Cari Banner</label>
            <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Judul atau Kode Promo...">
        </div>
        <div class="w-48">
            <label class="label-field text-xs uppercase tracking-widest opacity-60">Filter Status</label>
            <select name="status" class="input-field">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
            </select>
        </div>
        <button type="submit" class="btn-primary py-2.5 px-6">Filter</button>
        <a href="{{ route('admin.promo-banners.index') }}" class="btn-secondary py-2.5 px-6">Reset</a>
    </form>
</div>

<div class="table-container">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-warm-50/50">
                <th class="table-header w-48">Banner</th>
                <th class="table-header">Promo & Kode</th>
                <th class="table-header">Periode</th>
                <th class="table-header">Status</th>
                <th class="table-header text-center">Urutan</th>
                <th class="table-header text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-warm-100">
            @forelse($banners as $banner)
            <tr class="hover:bg-gray-warm-50/50 transition-colors">
                <td class="table-cell">
                    <div class="w-40 rounded-xl overflow-hidden border border-gray-warm-200 shadow-sm" style="aspect-ratio: 16/9;">
                        <img src="{{ $banner->image_url }}" class="w-full h-full object-cover" alt="{{ $banner->title }}">
                    </div>
                </td>
                <td class="table-cell">
                    <p class="font-bold text-dark mb-1">{{ $banner->title }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-100 uppercase">{{ $banner->promo_code }}</span>
                </td>
                <td class="table-cell">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs font-bold text-gray-warm-700">{{ $banner->start_date->format('d M') }} - {{ $banner->end_date->format('d M Y') }}</span>
                        @if($banner->is_active && !$banner->is_expired && now()->diffInDays($banner->end_date) <= 7)
                            <span class="text-[10px] font-bold text-amber-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Berakhir dlm {{ now()->diffInDays($banner->end_date) }} hari
                            </span>
                        @endif
                    </div>
                </td>
                <td class="table-cell">
                    @php
                        $status = $banner->status_label;
                        $badgeClass = match($status) {
                            'Aktif' => 'bg-green-100 text-green-700 border-green-200',
                            'Kadaluarsa' => 'bg-amber-100 text-amber-700 border-amber-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeClass }}">
                        {{ $status }}
                    </span>
                </td>
                <td class="table-cell text-center font-bold text-gray-warm-500">
                    {{ $banner->sort_order }}
                </td>
                <td class="table-cell">
                    <div class="flex items-center justify-end gap-3">
                        <button onclick="toggleBanner({{ $banner->id }})" class="p-2 rounded-lg hover:bg-blue-50 text-blue-600 transition-colors" title="Toggle Aktif/Nonaktif">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </button>
                        <a href="{{ route('admin.promo-banners.edit', $banner) }}" class="p-2 rounded-lg hover:bg-amber-50 text-amber-600 transition-colors" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <form action="{{ route('admin.promo-banners.destroy', $banner) }}" method="POST" id="delete-form-{{ $banner->id }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDeleteBanner({{ $banner->id }}, '{{ $banner->title }}', '{{ $banner->promo_code }}')" class="p-2 rounded-lg hover:bg-red-50 text-red-600 transition-colors" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-12 text-center">
                    <div class="max-w-xs mx-auto">
                        <svg class="w-16 h-16 text-gray-warm-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-gray-warm-400 font-medium">Belum ada banner promo yang ditambahkan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-8">
    {{ $banners->appends(request()->query())->links() }}
</div>

@push('scripts')
<script>
    function toggleBanner(id) {
        fetch(`/admin/promo-banners/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }

    function confirmDeleteBanner(id, title, code) {
        Swal.fire({
            title: 'Hapus Banner?',
            text: `Yakin ingin menghapus banner "${title}" dengan kode [${code}]?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#cc0000',
            cancelButtonColor: '#757575',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endpush
@endsection
