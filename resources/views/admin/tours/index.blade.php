@extends('layouts.admin')
@section('title', 'Kelola Paket Wisata - Admin')
@section('page-title', 'Kelola Paket Wisata')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-black text-dark">Daftar Paket</h1>
            <a href="{{ route('admin.tour-packages.create') }}" class="btn-primary btn-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Paket Baru
            </a>
        </div>

        <div class="card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-warm-100 text-xs font-bold text-gray-warm-600 uppercase tracking-widest">
                        <th class="px-6 py-4">Nama Paket</th>
                        <th class="px-6 py-4">Durasi</th>
                        <th class="px-6 py-4">Harga/Pax</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-warm-100">
                    @foreach($packages as $package)
                    <tr class="hover:bg-gray-warm-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-dark">
                            {{ $package->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-warm-600">
                            {{ $package->duration_days }} Hari
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-merah-600">
                            Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="{{ $package->status === 'active' ? 'badge-success' : 'badge-gray' }}">
                                {{ ucfirst($package->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.tour-packages.edit', $package) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.tour-packages.destroy', $package) }}" method="POST" id="delete-form-{{ $package->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete('Hapus paket ini?').then((result) => { if(result.isConfirmed) document.getElementById('delete-form-{{ $package->id }}').submit(); })"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-6">
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
