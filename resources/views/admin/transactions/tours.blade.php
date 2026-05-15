@extends('layouts.admin')
@section('title', 'Transaksi Paket Wisata - Admin')
@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h1 class="text-xl font-bold text-dark">Transaksi Paket Wisata</h1>
        <p class="text-gray-warm-500 text-sm">Kelola pesanan tour dan paket wisata</p>
    </div>
    
    <form action="{{ route('admin.transactions.tours') }}" method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Kode atau Nama..." 
               class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-500 outline-none w-48">
        
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-merah-500 outline-none">
            <option value="">Semua Status</option>
            @foreach(['pending', 'paid', 'cancelled', 'refunded'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        
        <button type="submit" class="bg-gray-100 p-2 rounded-xl hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
        
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.transactions.tours') }}" class="bg-red-50 p-2 rounded-xl text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        @endif
    </form>
</div>

<div class="space-y-6">

        <div class="card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-warm-100 text-xs font-bold text-gray-warm-600 uppercase tracking-widest">
                        <th class="px-6 py-4">Kode / Paket</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Tgl Berangkat</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4">Midtrans ID</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-warm-100">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-warm-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm">
                            <span class="font-bold text-merah-600 block">{{ $booking->booking_code }}</span>
                            <span class="text-gray-warm-500 text-xs">{{ $booking->tourPackage->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-medium text-dark block">{{ $booking->user->name }}</span>
                            <span class="text-gray-warm-400 text-xs">{{ $booking->user->email }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-warm-600">
                            {{ $booking->travel_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-dark">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-warm-600">
                            {{ $booking->payment_method ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-[10px] text-gray-warm-400 font-mono">
                            {{ $booking->payments->last()->midtrans_transaction_id ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-warm-600">
                            <span class="{{ match($booking->payment_status) { 'paid' => 'badge-success', 'pending' => 'badge-warning', 'cancelled' => 'badge-danger', 'refunded' => 'badge-info', default => 'badge-gray' } }} text-[10px]">
                                {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.transactions.tours.show', $booking) }}" class="btn-secondary btn-sm">Detail</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-6">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
