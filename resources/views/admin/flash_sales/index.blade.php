@extends('layouts.admin')
@section('title', 'Kelola Flash Sale - Admin')
@section('page-title', 'Kelola Flash Sale')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-black text-dark">Daftar Flash Sale</h1>
        <a href="{{ route('admin.flash-sales.create') }}" class="btn-primary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Flash Sale Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl flex items-center gap-3 mb-4">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-warm-100 text-xs font-bold text-gray-warm-600 uppercase tracking-widest">
                    <th class="px-6 py-4">Promo</th>
                    <th class="px-6 py-4">Target</th>
                    <th class="px-6 py-4">Diskon</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Kuota</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-warm-100">
                @forelse($flashSales as $sale)
                <tr class="hover:bg-gray-warm-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-bold text-dark">
                        {{ $sale->title }}
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-warm-600">
                        <span class="badge-gray uppercase">{{ str_replace('_', ' ', $sale->target_type) }}</span><br>
                        ID: {{ $sale->target_id }}
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-emerald-600">
                        {{ $sale->discount_type == 'percentage' ? $sale->discount_value.'%' : 'Rp '.number_format($sale->discount_value, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-[10px] text-gray-warm-600">
                        {{ $sale->start_time->format('d M, H:i') }} - <br>
                        {{ $sale->end_time->format('d M, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="w-24 bg-gray-200 rounded-full h-1.5 mb-1">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ ($sale->quota > 0) ? ($sale->used_quota / $sale->quota * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-warm-500">{{ $sale->used_quota }} / {{ $sale->quota }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @php
                            $now = now();
                            $active = $sale->is_active && $sale->start_time <= $now && $sale->end_time >= $now && $sale->used_quota < $sale->quota;
                        @endphp
                        <span class="{{ $active ? 'badge-success' : 'badge-gray' }}">
                            {{ $active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-right">
                        <div class="flex justify-end gap-2">
                            <form action="{{ route('admin.flash-sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400 italic text-sm">Belum ada promo flash sale.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-6">
            {{ $flashSales->links() }}
        </div>
    </div>
</div>
@endsection
