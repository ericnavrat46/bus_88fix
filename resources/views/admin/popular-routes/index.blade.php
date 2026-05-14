@extends('layouts.admin')
@section('title', 'Kelola Rute Populer')
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-black text-dark">Rute Populer</h1>
        <p class="text-gray-warm-500 text-sm">Kelola tampilan rute populer di halaman utama</p>
    </div>
    <a href="{{ route('admin.popular-routes.create') }}" class="btn-primary btn-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Rute Populer
    </a>
</div>

<div class="table-container">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-warm-50/50">
                <th class="table-header w-48">Gambar</th>
                <th class="table-header">Rute</th>
                <th class="table-header">Badge & Class</th>
                <th class="table-header text-center">Harga & Durasi</th>
                <th class="table-header text-center">Status</th>
                <th class="table-header text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-warm-100">
            @forelse($popularRoutes as $pr)
            <tr class="hover:bg-gray-warm-50/50 transition-colors">
                <td class="table-cell">
                    <div class="w-40 rounded-xl overflow-hidden border border-gray-warm-200 shadow-sm" style="aspect-ratio: 16/9;">
                        @if($pr->image)
                            <img src="{{ asset('storage/popular_routes/' . $pr->image) }}" class="w-full h-full object-cover" alt="{{ $pr->route->origin }}">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                </td>
                <td class="table-cell">
                    <p class="font-bold text-dark">{{ $pr->route->origin }} - {{ $pr->route->destination }}</p>
                    <p class="text-xs text-gray-warm-500">Urutan: {{ $pr->sort_order }}</p>
                </td>
                <td class="table-cell">
                    <div class="flex flex-col gap-1">
                        @if($pr->badge_text)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-red-50 text-red-700 border border-red-100 uppercase w-fit">{{ $pr->badge_text }}</span>
                        @endif
                        @if($pr->class_display)
                            <span class="text-xs font-bold text-gray-warm-600">{{ $pr->class_display }}</span>
                        @endif
                    </div>
                </td>
                <td class="table-cell text-center">
                    <p class="text-sm font-bold text-red-600">{{ $pr->price_display ?? 'Rp ' . number_format($pr->route->base_price, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-warm-500">{{ $pr->duration_display ?? $pr->route->formatted_duration }}</p>
                </td>
                <td class="table-cell text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $pr->is_active ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200' }}">
                        {{ $pr->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="table-cell">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.popular-routes.edit', $pr) }}" class="p-2 rounded-lg hover:bg-amber-50 text-amber-600 transition-colors" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <form action="{{ route('admin.popular-routes.destroy', $pr) }}" method="POST" id="delete-form-{{ $pr->id }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete({{ $pr->id }}, '{{ $pr->route->origin }} - {{ $pr->route->destination }}')" class="p-2 rounded-lg hover:bg-red-50 text-red-600 transition-colors" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-12 text-center text-gray-warm-400">Belum ada rute populer.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-8">
    {{ $popularRoutes->links() }}
</div>

@push('scripts')
<script>
    function confirmDelete(id, title) {
        Swal.fire({
            title: 'Hapus Rute Populer?',
            text: `Yakin ingin menghapus rute populer "${title}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#cc0000',
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
