@extends('layouts.admin')
@section('page-title', 'Manajemen Refund')
@section('content')
<div class="mb-8">
    <h1 class="text-xl font-bold text-dark">Permintaan Refund</h1>
    <p class="text-gray-warm-500 text-sm">Kelola pengembalian dana pembatalan tiket</p>
</div>

<div class="table-container overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr>
                <th class="table-header">Tanggal</th>
                <th class="table-header">Customer</th>
                <th class="table-header">Booking</th>
                <th class="table-header">Jumlah Refund</th>
                <th class="table-header">Rekening</th>
                <th class="table-header">Status</th>
                <th class="table-header">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($refunds as $refund)
        <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
            <td class="table-cell text-xs">{{ $refund->created_at->format('d/m/Y H:i') }}</td>
            <td class="table-cell">
                <p class="font-medium text-xs">{{ $refund->user->name }}</p>
                <p class="text-[10px] text-gray-warm-400">{{ $refund->user->phone }}</p>
            </td>
            <td class="table-cell">
                <p class="font-bold text-xs text-merah-600">{{ $refund->booking->booking_code }}</p>
                <p class="text-[10px] text-gray-warm-400">{{ $refund->booking->schedule->route->origin }} → {{ $refund->booking->schedule->route->destination }}</p>
            </td>
            <td class="table-cell font-bold text-merah-600 text-xs">Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</td>
            <td class="table-cell">
                <p class="text-[10px] font-bold">{{ $refund->bank_name }}</p>
                <p class="text-[10px]">{{ $refund->account_number }}</p>
                <p class="text-[10px] text-gray-warm-500">a.n {{ $refund->account_name }}</p>
            </td>
            <td class="table-cell">
                <span class="badge-{{ match($refund->status) { 'pending' => 'warning', 'approved' => 'info', 'rejected' => 'danger', 'completed' => 'success', default => 'gray' } }} text-[10px]">
                    {{ ucfirst($refund->status) }}
                </span>
            </td>
            <td class="table-cell">
                <a href="{{ route('admin.refund.edit', $refund) }}" class="btn-primary px-3 py-1.5 text-[10px] flex items-center justify-center gap-1 w-fit mx-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    KELOLA
                </a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $refunds->links() }}</div>
@endsection
