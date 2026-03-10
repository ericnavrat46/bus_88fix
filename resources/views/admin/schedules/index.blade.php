@extends('layouts.admin')
@section('page-title', 'Kelola Jadwal')
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-warm-500">Daftar jadwal keberangkatan</p>
    <a href="{{ route('admin.schedules.create') }}" class="btn-primary btn-sm">+ Tambah Jadwal</a>
</div>
<div class="table-container overflow-x-auto">
    <table class="w-full">
        <thead><tr><th class="table-header">Tanggal</th><th class="table-header">Jam</th><th class="table-header">Rute</th><th class="table-header">Bus</th><th class="table-header">Harga</th><th class="table-header">Kursi</th><th class="table-header">Status</th><th class="table-header">Aksi</th></tr></thead>
        <tbody>
        @foreach($schedules as $schedule)
        <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
            <td class="table-cell font-medium">{{ $schedule->departure_date->format('d/m/Y') }}</td>
            <td class="table-cell">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</td>
            <td class="table-cell text-xs">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</td>
            <td class="table-cell text-xs">{{ $schedule->bus->name }}</td>
            <td class="table-cell font-semibold text-merah-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</td>
            <td class="table-cell">{{ $schedule->available_seats }}</td>
            <td class="table-cell"><span class="{{ $schedule->status === 'active' ? 'badge-success' : 'badge-gray' }}">{{ ucfirst($schedule->status) }}</span></td>
            <td class="table-cell"><div class="flex items-center gap-2"><a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-sm text-merah-600 hover:underline font-medium">Edit</a><form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button type="submit" class="text-sm text-red-600 hover:underline font-medium">Hapus</button></form></div></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $schedules->links() }}</div>
@endsection
