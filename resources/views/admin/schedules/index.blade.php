@extends('layouts.admin')
@section('page-title', 'Kelola Jadwal')
@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-xl font-bold text-dark">Kelola Jadwal</h1>
        <p class="text-gray-warm-500 text-sm">Daftar jadwal keberangkatan bus</p>
    </div>
    
    <div class="flex flex-col md:flex-row gap-3">
        <form action="{{ route('admin.schedules.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari rute atau bus..." 
                   class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-500 outline-none w-full md:w-64">
            
            <input type="date" name="date" value="{{ request('date') }}" 
                   class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-500 outline-none">
            
            <button type="submit" class="bg-gray-100 p-2 rounded-xl hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
            
            @if(request()->anyFilled(['search', 'date']))
                <a href="{{ route('admin.schedules.index') }}" class="bg-red-50 p-2 rounded-xl text-red-500 hover:bg-red-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>

        <a href="{{ route('admin.schedules.create') }}" class="btn-primary flex items-center justify-center gap-2 text-sm px-4 py-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Jadwal
        </a>
    </div>
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
            <td class="table-cell">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-sm text-merah-600 hover:underline font-medium">Edit</a>
                    <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" id="delete-form-{{ $schedule->id }}">
                        @csrf @method('DELETE')
                        <button type="button" 
                                onclick="confirmDelete('Hapus jadwal ini?').then((result) => { if(result.isConfirmed) document.getElementById('delete-form-{{ $schedule->id }}').submit(); })"
                                class="text-sm text-red-600 hover:underline font-medium">
                            Hapus
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $schedules->links() }}</div>
@endsection
