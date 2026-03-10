@extends('layouts.admin')
@section('page-title', 'Pembayaran')
@section('content')
<p class="text-gray-warm-500 mb-6">Semua transaksi pembayaran via Midtrans</p>
<div class="table-container overflow-x-auto">
    <table class="w-full">
        <thead><tr><th class="table-header">Order ID</th><th class="table-header">Tipe</th><th class="table-header">Jumlah</th><th class="table-header">Metode</th><th class="table-header">Status</th><th class="table-header">Midtrans ID</th><th class="table-header">Waktu</th></tr></thead>
        <tbody>
        @foreach($payments as $payment)
        <tr class="border-b border-gray-warm-50 hover:bg-gray-warm-50">
            <td class="table-cell font-semibold text-dark text-xs tracking-wider">{{ $payment->midtrans_order_id }}</td>
            <td class="table-cell text-xs">{{ class_basename($payment->payable_type) }}</td>
            <td class="table-cell font-semibold text-merah-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            <td class="table-cell text-xs">{{ $payment->payment_type ?? '-' }}</td>
            <td class="table-cell"><span class="{{ match($payment->status) { 'settlement','capture' => 'badge-success', 'pending' => 'badge-warning', 'expire' => 'badge-gray', 'cancel','deny' => 'badge-danger', default => 'badge-info' } }}">{{ ucfirst($payment->status) }}</span></td>
            <td class="table-cell text-xs text-gray-warm-400">{{ $payment->midtrans_transaction_id ?? '-' }}</td>
            <td class="table-cell text-xs">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $payments->links() }}</div>
@endsection
