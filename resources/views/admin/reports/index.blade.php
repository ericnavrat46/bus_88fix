@extends('layouts.admin')
@section('page-title', 'Laporan')
@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <p class="text-gray-warm-500">Laporan transaksi berdasarkan periode dan jenis</p>
    </div>
    <a href="{{ route('admin.reports.print', request()->query()) }}" target="_blank"
       class="btn-primary btn-sm flex items-center gap-2 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Cetak / Ekspor PDF
    </a>
</div>

{{-- Filter Form --}}
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-warm-500 uppercase tracking-wider mb-1">Jenis Laporan</label>
            <select name="type" class="input-field">
                <option value="booking"   {{ $type === 'booking'   ? 'selected' : '' }}>Booking Tiket</option>
                <option value="rental"    {{ $type === 'rental'    ? 'selected' : '' }}>Sewa / Charter</option>
                <option value="tour"      {{ $type === 'tour'      ? 'selected' : '' }}>Paket Wisata</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-warm-500 uppercase tracking-wider mb-1">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="input-field">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-warm-500 uppercase tracking-wider mb-1">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="input-field">
        </div>
        <div>
            <button type="submit" class="btn-primary w-full">Tampilkan Laporan</button>
        </div>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-5 text-center border-l-4 border-merah-500">
        <p class="text-2xl font-black text-dark">{{ $summary['total'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Total Transaksi</p>
    </div>

    @if($type === 'booking')
    <div class="card p-5 text-center border-l-4 border-emerald-500">
        <p class="text-2xl font-black text-dark">{{ $summary['paid'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Lunas</p>
    </div>
    <div class="card p-5 text-center border-l-4 border-amber-400">
        <p class="text-2xl font-black text-dark">{{ $summary['pending'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Menunggu</p>
    </div>
    <div class="card p-5 text-center border-l-4 border-red-400">
        <p class="text-2xl font-black text-dark">{{ $summary['cancelled'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Batal / Expired</p>
    </div>

    @elseif($type === 'rental')
    <div class="card p-5 text-center border-l-4 border-emerald-500">
        <p class="text-2xl font-black text-dark">{{ $summary['approved'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Disetujui</p>
    </div>
    <div class="card p-5 text-center border-l-4 border-amber-400">
        <p class="text-2xl font-black text-dark">{{ $summary['pending'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Menunggu</p>
    </div>
    <div class="card p-5 text-center border-l-4 border-red-400">
        <p class="text-2xl font-black text-dark">{{ $summary['rejected'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Ditolak</p>
    </div>

    @elseif($type === 'tour')
    <div class="card p-5 text-center border-l-4 border-emerald-500">
        <p class="text-2xl font-black text-dark">{{ $summary['paid'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Lunas</p>
    </div>
    <div class="card p-5 text-center border-l-4 border-amber-400">
        <p class="text-2xl font-black text-dark">{{ $summary['pending'] }}</p>
        <p class="text-xs text-gray-warm-500 mt-1">Menunggu</p>
    </div>
    <div class="card p-5 text-center border-l-4 border-blue-400">
        <p class="text-2xl font-black text-dark">-</p>
        <p class="text-xs text-gray-warm-500 mt-1">&nbsp;</p>
    </div>
    @endif

</div>

{{-- Revenue Total --}}
<div class="card p-6 mb-6 bg-gradient-to-r from-merah-600 to-merah-800 text-white">
    <p class="text-sm font-medium text-merah-100 mb-1">Total Pendapatan (Lunas)</p>
    <p class="text-3xl font-black">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p>
    <p class="text-xs text-merah-200 mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} – {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</p>
</div>

{{-- Data Table --}}
<div class="table-container overflow-x-auto" style="overflow: visible;">
    <table class="w-full">

        @if($type === 'booking')
        <thead>
            <tr>
                <th class="table-header">No</th>
                <th class="table-header">Kode</th>
                <th class="table-header">Customer</th>
                <th class="table-header">Rute</th>
                <th class="table-header">Tgl Keberangkatan</th>
                <th class="table-header">Kursi</th>
                <th class="table-header">Total</th>
                <th class="table-header">Status</th>
                <th class="table-header">Tgl Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $booking)
            <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
                <td class="table-cell text-gray-warm-400 text-xs">{{ $loop->iteration }}</td>
                <td class="table-cell font-semibold text-xs tracking-wider">{{ $booking->booking_code }}</td>
                <td class="table-cell">
                    <p class="font-medium text-sm">{{ $booking->user->name }}</p>
                    <p class="text-xs text-gray-warm-400">{{ $booking->user->email }}</p>
                </td>
                <td class="table-cell text-xs">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</td>
                <td class="table-cell text-xs">{{ $booking->schedule->departure_date->format('d/m/Y') }}</td>
                <td class="table-cell">{{ $booking->total_seats }}</td>
                <td class="table-cell font-semibold text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td class="table-cell">
                    <span class="{{ match($booking->payment_status) {
                        'paid' => 'badge-success',
                        'pending' => 'badge-warning',
                        'refunded' => 'badge-info',
                        'cancelled', 'expired' => 'badge-danger',
                        default => 'badge-gray'
                    } }}">{{ ucfirst($booking->payment_status) }}</span>
                </td>
                <td class="table-cell text-xs text-gray-warm-400">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" class="table-cell text-center text-gray-warm-400 py-8">Tidak ada data di periode ini.</td></tr>
            @endforelse
        </tbody>

        @elseif($type === 'rental')
        <thead>
            <tr>
                <th class="table-header">No</th>
                <th class="table-header">Kode</th>
                <th class="table-header">Customer</th>
                <th class="table-header">Rute</th>
                <th class="table-header">Periode</th>
                <th class="table-header">Bus</th>
                <th class="table-header">Total</th>
                <th class="table-header">Status</th>
                <th class="table-header">Tgl Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $rental)
            <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
                <td class="table-cell text-gray-warm-400 text-xs">{{ $loop->iteration }}</td>
                <td class="table-cell font-semibold text-xs tracking-wider">{{ $rental->rental_code }}</td>
                <td class="table-cell">
                    <p class="font-medium text-sm">{{ $rental->user->name }}</p>
                    <p class="text-xs text-gray-warm-400">{{ $rental->user->email }}</p>
                </td>
                <td class="table-cell text-xs">{{ $rental->pickup_location }} → {{ $rental->destination }}</td>
                <td class="table-cell text-xs">{{ $rental->start_date->format('d/m/Y') }} – {{ $rental->end_date->format('d/m/Y') }} ({{ $rental->duration_days }}h)</td>
                <td class="table-cell text-xs">{{ $rental->bus?->name ?? '-' }}</td>
                <td class="table-cell font-semibold text-merah-600">{{ $rental->total_price ? 'Rp ' . number_format($rental->total_price, 0, ',', '.') : '-' }}</td>
                <td class="table-cell">
                    <span class="{{ match($rental->approval_status) {
                        'approved' => 'badge-success',
                        'pending' => 'badge-warning',
                        'rejected' => 'badge-danger',
                        default => 'badge-gray'
                    } }}">{{ ucfirst($rental->approval_status) }}</span>
                </td>
                <td class="table-cell text-xs text-gray-warm-400">{{ $rental->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" class="table-cell text-center text-gray-warm-400 py-8">Tidak ada data di periode ini.</td></tr>
            @endforelse
        </tbody>

        @elseif($type === 'tour')
        <thead>
            <tr>
                <th class="table-header">No</th>
                <th class="table-header">Kode</th>
                <th class="table-header">Customer</th>
                <th class="table-header">Paket</th>
                <th class="table-header">Tgl Wisata</th>
                <th class="table-header">Peserta</th>
                <th class="table-header">Total</th>
                <th class="table-header">Status</th>
                <th class="table-header">Tgl Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $booking)
            <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
                <td class="table-cell text-gray-warm-400 text-xs">{{ $loop->iteration }}</td>
                <td class="table-cell font-semibold text-xs tracking-wider">{{ $booking->booking_code }}</td>
                <td class="table-cell">
                    <p class="font-medium text-sm">{{ $booking->user->name }}</p>
                    <p class="text-xs text-gray-warm-400">{{ $booking->user->email }}</p>
                </td>
                <td class="table-cell text-sm">{{ $booking->tourPackage?->name ?? '-' }}</td>
                <td class="table-cell text-xs">{{ $booking->travel_date?->format('d/m/Y') ?? '-' }}</td>
                <td class="table-cell">{{ $booking->passenger_count }}</td>
                <td class="table-cell font-semibold text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td class="table-cell">
                    <span class="{{ match($booking->payment_status) {
                        'paid' => 'badge-success',
                        'pending' => 'badge-warning',
                        default => 'badge-gray'
                    } }}">{{ ucfirst($booking->payment_status) }}</span>
                </td>
                <td class="table-cell text-xs text-gray-warm-400">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" class="table-cell text-center text-gray-warm-400 py-8">Tidak ada data di periode ini.</td></tr>
            @endforelse
        </tbody>
        @endif

    </table>
</div>
@endsection
