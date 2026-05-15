@extends('layouts.app')
@section('title', 'Detail Booking - Bus 88')

@push('styles')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-merah-600 hover:underline mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Dashboard
        </a>

        <div class="card p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-dark">E-Tiket</h1>
                @php
                    $statusClass = match($booking->payment_status) {
                        'paid' => 'badge-success',
                        'pending' => 'badge-warning',
                        'expired' => 'badge-gray',
                        'cancelled' => 'badge-danger',
                        'refunded' => 'badge-info',
                        default => 'badge-gray',
                    };
                    $statusLabel = match($booking->payment_status) {
                        'paid' => 'Telah Terbayar',
                        'pending' => 'Menunggu Bayar',
                        'expired' => 'Kedaluwarsa',
                        'cancelled' => 'Dibatalkan',
                        'refunded' => 'Refund',
                        default => $booking->payment_status,
                    };
                @endphp
                <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            {{-- Ticket Card --}}
            <div class="bg-gradient-to-br from-merah-600 to-merah-800 rounded-2xl p-8 text-white mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center"><span class="font-black">88</span></div>
                        <span class="font-bold text-lg">Bus 88</span>
                    </div>
                    <div class="flex items-center gap-6 mb-6">
                        <div>
                            <p class="text-3xl font-black">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</p>
                            <p class="text-sm text-white/70">{{ $booking->schedule->route->origin }}</p>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                            <div class="flex-1 border-t border-dashed border-white/30"></div>
                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-black">{{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }}</p>
                            <p class="text-sm text-white/70">{{ $booking->schedule->route->destination }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div><p class="text-white/50 text-xs">Tanggal</p><p class="font-semibold">{{ $booking->schedule->departure_date->translatedFormat('d M Y') }}</p></div>
                        <div><p class="text-white/50 text-xs">Bus</p><p class="font-semibold">{{ $booking->schedule->bus->name }}</p></div>
                        <div><p class="text-white/50 text-xs">Kode</p><p class="font-semibold tracking-wider">{{ $booking->booking_code }}</p></div>
                    </div>
                </div>
            </div>

            {{-- Passengers --}}
            <h3 class="font-bold text-dark mb-3">Daftar Penumpang</h3>
            <div class="space-y-2 mb-6">
                @foreach($booking->passengers as $p)
                <div class="flex items-center gap-3 p-4 bg-gray-warm-50 rounded-xl">
                    <div class="w-10 h-10 bg-merah-100 rounded-lg flex items-center justify-center text-sm font-bold text-merah-600">{{ $p->seat_number }}</div>
                    <div>
                        <p class="font-semibold text-dark">{{ $p->passenger_name }}</p>
                        <p class="text-xs text-gray-warm-500">Kursi #{{ $p->seat_number }} {{ $p->id_number ? '· ' . $p->id_number : '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <hr class="border-gray-warm-100 mb-6">
            <div class="flex items-center justify-between mb-8">
                <span class="text-gray-warm-500">Total</span>
                <span class="text-2xl font-black text-merah-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>

            {{-- Payment Section --}}
            @if($booking->payment_status === 'pending' && !$booking->isExpired())
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div id="payment-status-checking" class="hidden mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100 text-center animate-pulse">
                        <p class="text-sm text-blue-600 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Sinkronisasi status pembayaran...
                        </p>
                    </div>

                            <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100 text-center">
                                <h4 class="font-bold text-dark mb-2">Selesaikan Pembayaran</h4>
                                <p class="text-sm text-gray-warm-500 mb-6">Gunakan metode pembayaran otomatis (Midtrans) untuk memproses tiket Anda.</p>
                                
                                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                    <button onclick="payNow()" class="btn-primary px-8 py-3">
                                        BAYAR SEKARANG
                                    </button>
                                    <button onclick="syncStatus()" id="syncBtn" class="btn-secondary px-8 py-3 flex items-center justify-center gap-2">
                                        CEK STATUS
                                    </button>
                                </div>
                            </div>
                </div>
            @elseif($booking->payment_status === 'refunded')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        </div>
                        <h4 class="font-bold text-blue-800 mb-1">Tiket Telah Direfund</h4>
                        <p class="text-sm text-blue-600">Dana pembayaran Anda sedang diproses untuk dikembalikan. Hubungi admin jika ada pertanyaan.</p>
                    </div>
                </div>
            @elseif($booking->payment_status === 'cancelled')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-red-50 rounded-2xl border border-red-100 text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h4 class="font-bold text-red-800 mb-1">Pesanan Dibatalkan</h4>
                        <p class="text-sm text-red-600">Pesanan ini telah dibatalkan.</p>
                    </div>
                </div>
            @elseif($booking->payment_status === 'expired')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-bold text-gray-700 mb-1">Pesanan Kedaluwarsa</h4>
                        <p class="text-sm text-gray-500">Batas waktu pembayaran telah habis.</p>
                    </div>
                </div>
            @elseif($booking->payment_status === 'paid' || $booking->payment_status === 'pending_refund')
                @php 
                    $refund = \App\Models\Refund::where('booking_id', $booking->id)->first();
                    $departure = \Carbon\Carbon::parse($booking->schedule->departure_date->format('Y-m-d') . ' ' . $booking->schedule->departure_time);
                    $canRefund = now()->diffInHours($departure, false) >= 24;
                @endphp
                
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    @if($booking->payment_status === 'pending_refund' || $refund)
                        <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-amber-800">Menunggu Verifikasi Refund</h4>
                                    <p class="text-xs text-amber-600 italic">Tiket tidak dapat digunakan selama proses pemeriksaan.</p>
                                </div>
                            </div>
                            @if($refund && $refund->status !== 'pending')
                            <div class="p-4 bg-white/50 rounded-xl text-sm text-amber-700">
                                <p class="font-bold mb-1">Status Verifikasi: {{ ucfirst($refund->status) }}</p>
                                <p class="italic text-xs">{{ $refund->admin_notes ?? 'Sedang ditinjau oleh Admin...' }}</p>
                            </div>
                            @endif
                        </div>
                    @else
                        @if($canRefund)
                            <div class="p-6 bg-gray-warm-50 rounded-2xl border border-gray-warm-100">
                                <h4 class="font-bold text-dark mb-2">Punya Rencana Lain?</h4>
                                <p class="text-sm text-gray-warm-500 mb-4">Anda dapat mengajukan refund dengan aturan berikut:</p>
                                <ul class="text-[11px] text-gray-400 space-y-1 mb-6 list-disc list-inside">
                                    <li>Maksimal H-1 sebelum berangkat: <strong>Refund Tersedia</strong></li>
                                    <li>Kurang dari 24 jam sebelum berangkat: <strong>Refund Tidak Tersedia</strong></li>
                                </ul>
                                <a href="{{ route('booking.refund', $booking) }}" class="btn-secondary w-full py-3 text-sm font-bold text-center">
                                    AJUKAN REFUND
                                </a>
                            </div>
                        @else
                            <div class="p-4 bg-gray-100 rounded-xl text-center">
                                <p class="text-xs text-gray-400 italic font-medium">Batas waktu refund habis (minimal 24 jam/H-1 sebelum berangkat).</p>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    function payNow() {
        window.snap.pay("{{ $booking->snap_token }}", {
            onSuccess: function(result) {
                syncStatus();
            },
            onPending: function(result) {
                syncStatus();
            },
            onError: function(result) {
                console.error(result);
            }
        });
    }

    function syncStatus() {
        const orderId = "{{ $booking->midtrans_order_id ?? $booking->booking_code }}";
        const checkingDiv = document.getElementById('payment-status-checking');
        const syncBtn = document.getElementById('syncBtn');

        if (checkingDiv) checkingDiv.classList.remove('hidden');
        if (syncBtn) syncBtn.disabled = true;

        fetch(`/api/payments/check/${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status && data.payment_status === 'settlement' || data.payment_status === 'paid') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Tiket Anda telah lunas.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    if (checkingDiv) checkingDiv.classList.add('hidden');
                    if (syncBtn) syncBtn.disabled = false;
                    
                    if (data.payment_status === 'pending') {
                        // Keep as is or show message
                    } else {
                        window.location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error syncing status:', error);
                if (checkingDiv) checkingDiv.classList.add('hidden');
                if (syncBtn) syncBtn.disabled = false;
            });
    }

    // Auto sync on page load if pending
    @if($booking->payment_status === 'pending')
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(syncStatus, 1000);
    });
    @endif
</script>
@endpush
@endsection
