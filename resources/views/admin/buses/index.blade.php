@extends('layouts.admin')
@section('page-title', 'Kelola Bus')
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-warm-500">Daftar semua bus yang tersedia</p>
    <a href="{{ route('admin.buses.create') }}" class="btn-primary btn-sm">+ Tambah Bus</a>
</div>
<div class="table-container">
    <table class="w-full">
        <thead><tr>
            <th class="table-header">Kode</th><th class="table-header">Nama</th><th class="table-header">Tipe</th><th class="table-header">Kapasitas</th><th class="table-header">Plat</th><th class="table-header">Status</th><th class="table-header">Aksi</th>
        </tr></thead>
        <tbody>
        @foreach($buses as $bus)
        <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
            <td class="table-cell font-semibold text-dark">{{ $bus->code }}</td>
            <td class="table-cell font-medium">{{ $bus->name }}</td>
            <td class="table-cell"><span class="badge {{ $bus->type === 'eksekutif' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">{{ ucfirst($bus->type) }}</span></td>
            <td class="table-cell">{{ $bus->capacity }} kursi</td>
            <td class="table-cell text-xs tracking-wider">{{ $bus->plate_number }}</td>
            <td class="table-cell"><span class="badge {{ match($bus->status) { 'active' => 'badge-success', 'maintenance' => 'badge-warning', default => 'badge-gray' } }}">{{ ucfirst($bus->status) }}</span></td>
            <td class="table-cell">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.buses.edit', $bus) }}" class="text-sm text-merah-600 hover:underline font-medium">Edit</a>
                    <form method="POST" action="{{ route('admin.buses.destroy', $bus) }}" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline font-medium">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $buses->links() }}</div>
@endsection
