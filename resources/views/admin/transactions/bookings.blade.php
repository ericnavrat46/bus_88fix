@extends('layouts.admin')
@section('page-title', 'Booking Tiket')
@section('content')
<p class="text-gray-warm-500 mb-6">Monitor semua transaksi booking tiket</p>
<div class="table-container overflow-x-auto" style="overflow: visible;">
    <table class="w-full">
        <thead><tr><th class="table-header">Kode</th><th class="table-header">Customer</th><th class="table-header">Rute</th><th class="table-header">Tanggal</th><th class="table-header">Kursi</th><th class="table-header">Total</th><th class="table-header">Status</th><th class="table-header">Aksi</th></tr></thead>
        <tbody>
        @foreach($bookings as $booking)
        <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
            <td class="table-cell font-semibold text-dark text-xs tracking-wider">{{ $booking->booking_code }}</td>
            <td class="table-cell"><p class="font-medium">{{ $booking->user->name }}</p><p class="text-xs text-gray-warm-400">{{ $booking->user->email }}</p></td>
            <td class="table-cell text-xs">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</td>
            <td class="table-cell text-xs">{{ $booking->schedule->departure_date->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</td>
            <td class="table-cell">{{ $booking->total_seats }}</td>
            <td class="table-cell font-semibold text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
            <td class="table-cell"><span class="{{ match($booking->payment_status) { 'paid' => 'badge-success', 'pending' => 'badge-warning', 'expired' => 'badge-gray', 'cancelled' => 'badge-danger', default => 'badge-info' } }}">{{ ucfirst($booking->payment_status) }}</span></td>
            <td class="table-cell">
                <div class="flex flex-col gap-2">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-xs text-merah-600 hover:underline font-medium">Ubah Status</button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-warm-100 py-2 z-50">
                            @foreach(['pending','paid','expired','cancelled','refunded'] as $status)
                            <form method="POST" action="{{ route('admin.booking.status', $booking) }}">@csrf @method('PATCH')
                                <input type="hidden" name="payment_status" value="{{ $status }}">
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-warm-50 {{ $booking->payment_status === $status ? 'text-merah-600 font-bold' : 'text-gray-warm-700' }}">{{ ucfirst($status) }}</button>
                            </form>
                            @endforeach
                        </div>
                    </div>

                    @if($booking->payment_proof)
                    <div x-data="{ modal: false }">
                        <button @click="modal = true" class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-1 rounded border border-emerald-100 hover:bg-emerald-100 transition-colors">
                            Lihat Bukti
                        </button>
                        
                        {{-- Modal Bukti --}}
                        <div x-show="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-dark/50 backdrop-blur-sm" style="display: none;">
                            <div @click.away="modal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="font-bold text-dark">Bukti Pembayaran #{{ $booking->booking_code }}</h3>
                                    <button @click="modal = false" class="text-gray-warm-400 hover:text-dark">✕</button>
                                </div>
                                <img src="{{ asset('storage/' . $booking->payment_proof) }}" class="w-full rounded-xl mb-6 shadow-lg border border-gray-warm-100">
                                <div class="flex gap-3">
                                    <form method="POST" action="{{ route('admin.booking.approve-manual', $booking) }}" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full btn-primary py-2 text-sm">Setujui Pembayaran</button>
                                    </form>
                                    <button @click="modal = false" class="flex-1 btn-secondary py-2 text-sm">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
