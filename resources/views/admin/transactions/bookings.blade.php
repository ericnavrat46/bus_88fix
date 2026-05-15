@extends('layouts.admin')
@section('page-title', 'Booking Tiket')
@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h1 class="text-xl font-bold text-dark">Booking Tiket Bus</h1>
        <p class="text-gray-warm-500 text-sm">Monitor semua transaksi booking tiket</p>
    </div>
    
    <form action="{{ route('admin.transactions.bookings') }}" method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Kode atau Nama..." 
               class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-500 outline-none w-48">
        
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-500 outline-none">
            <option value="">Semua Status</option>
            @foreach(['pending','paid','cancelled','refunded'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        
        <button type="submit" class="bg-gray-100 p-2 rounded-xl hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
        
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.transactions.bookings') }}" class="bg-red-50 p-2 rounded-xl text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        @endif
    </form>
</div>

<div class="table-container overflow-x-auto" style="overflow: visible;">
    <table class="w-full">
        <thead><tr><th class="table-header">Kode</th><th class="table-header">Customer</th><th class="table-header">Rute</th><th class="table-header">Tanggal</th><th class="table-header">Total</th><th class="table-header">Metode</th><th class="table-header">Midtrans ID</th><th class="table-header">Status</th><th class="table-header">Aksi</th></tr></thead>
        <tbody>
        @foreach($bookings as $booking)
        <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
            <td class="table-cell font-semibold text-dark text-xs tracking-wider">{{ $booking->booking_code }}</td>
            <td class="table-cell"><p class="font-medium">{{ $booking->user->name }}</p><p class="text-xs text-gray-warm-400">{{ $booking->user->email }}</p></td>
            <td class="table-cell text-xs">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</td>
            <td class="table-cell text-xs">{{ $booking->schedule->departure_date->format('d/m/Y') }}</td>
            <td class="table-cell font-semibold text-merah-600 text-xs">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
            <td class="table-cell text-xs text-gray-warm-600">{{ $booking->payment_method ?? '-' }}</td>
            <td class="table-cell text-[10px] text-gray-warm-400 font-mono">{{ $booking->latestPayment->midtrans_transaction_id ?? '-' }}</td>
            <td class="table-cell"><span class="{{ match($booking->payment_status) { 'paid' => 'badge-success', 'pending' => 'badge-warning', 'cancelled' => 'badge-danger', 'refunded' => 'badge-info', 'pending_refund' => 'badge-warning', default => 'badge-gray' } }} text-[10px]">{{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}</span></td>
            <td class="table-cell">
                <div class="flex flex-col gap-2">
                    <form method="POST" action="{{ route('admin.booking.status', $booking) }}">
                        @csrf @method('PATCH')
                        @if(in_array($booking->payment_status, ['paid', 'cancelled', 'refunded', 'pending_refund']))
                            <div class="text-[10px] font-bold text-gray-400 bg-gray-50 border border-gray-100 rounded px-2 py-1 text-center italic">
                                Sesuai Sistem
                            </div>
                        @else
                            <select name="payment_status" onchange="this.form.submit()" class="text-xs border border-gray-300 rounded-lg px-2 py-1 focus:ring-merah-500 outline-none w-full min-w-[100px] cursor-pointer bg-white">
                                @foreach(['pending','cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $booking->payment_status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
