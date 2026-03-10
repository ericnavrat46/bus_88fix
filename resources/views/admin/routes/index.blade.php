@extends('layouts.admin')
@section('page-title', 'Kelola Rute')
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-warm-500">Daftar rute perjalanan</p>
    <a href="{{ route('admin.routes.create') }}" class="btn-primary btn-sm">+ Tambah Rute</a>
</div>
<div class="table-container">
    <table class="w-full">
        <thead><tr><th class="table-header">Asal</th><th class="table-header">Tujuan</th><th class="table-header">Jarak</th><th class="table-header">Durasi</th><th class="table-header">Harga Dasar</th><th class="table-header">Status</th><th class="table-header">Aksi</th></tr></thead>
        <tbody>
        @foreach($routes as $route)
        <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
            <td class="table-cell font-medium">{{ $route->origin }}</td>
            <td class="table-cell font-medium">{{ $route->destination }}</td>
            <td class="table-cell">{{ $route->distance ? $route->distance.' km' : '-' }}</td>
            <td class="table-cell">{{ $route->formatted_duration }}</td>
            <td class="table-cell font-semibold text-merah-600">Rp {{ number_format($route->base_price, 0, ',', '.') }}</td>
            <td class="table-cell"><span class="{{ $route->status === 'active' ? 'badge-success' : 'badge-gray' }}">{{ ucfirst($route->status) }}</span></td>
            <td class="table-cell"><div class="flex items-center gap-2"><a href="{{ route('admin.routes.edit', $route) }}" class="text-sm text-merah-600 hover:underline font-medium">Edit</a><form method="POST" action="{{ route('admin.routes.destroy', $route) }}" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button type="submit" class="text-sm text-red-600 hover:underline font-medium">Hapus</button></form></div></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $routes->links() }}</div>
@endsection
