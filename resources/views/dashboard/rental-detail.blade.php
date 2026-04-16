@extends('layouts.app')
@section('title', 'Detail Sewa Bus - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-merah-600 hover:underline mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Dashboard
        </a>

        <div class="card p-8">
            @if($rental->approval_status === 'approved' && in_array($rental->payment_status, ['unpaid', 'pending']))
            <div class="mb-8 p-6 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-4 shadow-sm animate-pulse-subtle">
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-amber-900 leading-tight">Batas Waktu Pembayaran</h4>
                    <p class="text-sm text-amber-800">Sewa bus ini akan dibatalkan otomatis jika tidak dibayar sebelum <span class="font-black underline">{{ $rental->updated_at->addHours(2)->translatedFormat('H:i') }} WIB</span> (2 jam setelah disetujui).</p>
                </div>
            </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-dark">Detail Sewa Bus</h1>
                @php
                    $approvalClass = match($rental->approval_status) {
                        'approved' => 'badge-success',
                        'pending'  => 'badge-warning',
                        'rejected' => 'badge-danger',
                        default    => 'badge-gray',
                    };
                    $approvalLabel = match($rental->approval_status) {
                        'approved' => 'Disetujui',
                        'pending'  => 'Menunggu Persetujuan',
                        'rejected' => 'Ditolak',
                        default    => ucfirst($rental->approval_status),
                    };
                @endphp
                <span class="{{ $approvalClass }}">{{ $approvalLabel }}</span>
            </div>

            {{-- Charter Card --}}
            <div class="bg-gradient-to-br from-merah-600 to-merah-800 rounded-2xl p-8 text-white mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center"><span class="font-black">88</span></div>
                        <span class="font-bold text-lg">Bus 88 — Charter / Sewa</span>
                    </div>
                    <div class="flex items-center gap-6 mb-6">
                        <div>
                            <p class="text-2xl font-black">{{ $rental->pickup_location }}</p>
                            <p class="text-sm text-white/70">Lokasi Jemput</p>
                        </div>
                        <div class="flex-1 flex items-center gap-2">
                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                            <div class="flex-1 border-t border-dashed border-white/30"></div>
                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-black">{{ $rental->destination }}</p>
                            <p class="text-sm text-white/70">Tujuan</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div><p class="text-white/50 text-xs">Mulai</p><p class="font-semibold">{{ $rental->start_date->translatedFormat('d M Y') }}</p></div>
                        <div><p class="text-white/50 text-xs">Selesai</p><p class="font-semibold">{{ $rental->end_date->translatedFormat('d M Y') }}</p></div>
                        <div><p class="text-white/50 text-xs">Kode</p><p class="font-semibold tracking-wider text-xs">{{ $rental->rental_code }}</p></div>
                    </div>
                </div>
            </div>

            {{-- Info Detail --}}
            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center py-3 border-b border-gray-warm-50">
                    <span class="text-gray-warm-500 text-sm">Durasi</span>
                    <span class="font-semibold text-dark">{{ $rental->duration_days }} hari</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-warm-50">
                    <span class="text-gray-warm-500 text-sm">Jumlah Penumpang</span>
                    <span class="font-semibold text-dark">{{ $rental->passenger_count }} orang</span>
                </div>
                @if($rental->purpose)
                <div class="flex justify-between items-center py-3 border-b border-gray-warm-50">
                    <span class="text-gray-warm-500 text-sm">Keperluan</span>
                    <span class="font-semibold text-dark">{{ $rental->purpose }}</span>
                </div>
                @endif
                @if($rental->bus)
                <div class="flex justify-between items-center py-3 border-b border-gray-warm-50">
                    <span class="text-gray-warm-500 text-sm">Bus</span>
                    <span class="font-semibold text-dark">{{ $rental->bus->name }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center py-3 border-b border-gray-warm-50">
                    <span class="text-gray-warm-500 text-sm">Kontak</span>
                    <span class="font-semibold text-dark">{{ $rental->contact_name }} · {{ $rental->contact_phone }}</span>
                </div>
            </div>

            @if($rental->total_price)
            <div class="flex items-center justify-between mb-8 p-4 bg-merah-50 rounded-xl">
                <span class="text-gray-warm-500">Total Harga</span>
                <span class="text-2xl font-black text-merah-600">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span>
            </div>
            @endif

            {{-- Admin Notes --}}
            @if($rental->admin_notes)
            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 mb-6">
                <p class="text-sm text-blue-800"><strong>Catatan Admin:</strong> {{ $rental->admin_notes }}</p>
            </div>
            @endif

            {{-- Payment Section --}}
            @if($rental->approval_status === 'approved' && $rental->payment_status !== 'paid' && $rental->total_price)
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    @if($rental->payment_proof)
                        <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h4 class="font-bold text-emerald-800 mb-1">Bukti Sudah Diunggah</h4>
                            <p class="text-sm text-emerald-600 mb-6">Mohon tunggu verifikasi admin.</p>
                            <img src="{{ asset('storage/' . $rental->payment_proof) }}" class="max-w-xs mx-auto rounded-xl shadow-md border border-emerald-200 mb-6">
                            <div class="mt-4">
                                <p class="text-xs text-emerald-500 mb-2 font-medium italic">Ganti bukti pembayaran?</p>
                                <form action="{{ route('rental.upload-proof', $rental) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 max-w-xs mx-auto">
                                    @csrf
                                    <input type="file" name="payment_proof" class="text-xs file:btn-secondary file:btn-xs" required accept="image/*">
                                    <button type="submit" class="p-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="space-y-6">
                            <a href="{{ route('rental.pay', $rental) }}" class="btn-primary w-full text-center py-4 text-lg font-bold shadow-lg block">BAYAR VIA MIDTRANS</a>

                            <div class="flex items-center gap-4">
                                <div class="h-px flex-1 bg-gray-warm-200"></div>
                                <span class="text-xs font-bold text-gray-warm-400">ATAU TRANSFER MANUAL</span>
                                <div class="h-px flex-1 bg-gray-warm-200"></div>
                            </div>

                            <div class="p-6 bg-gray-warm-50 rounded-2xl border border-gray-warm-100">
                                <h4 class="font-bold text-dark mb-4 text-center">Formulir Bukti Transfer</h4>
                                <div class="p-4 bg-white rounded-xl border border-gray-warm-200 text-sm mb-4">
                                    <p class="text-gray-warm-500 mb-1">Transfer ke BRI:</p>
                                    <p class="text-lg font-black text-merah-600">1234-5678-9012-345</p>
                                    <p class="text-xs text-gray-warm-400">a.n. PT Bus 88 Merah Putih</p>
                                </div>
                                <form action="{{ route('rental.upload-proof', $rental) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <input type="file" name="payment_proof" class="input-field py-2.5 text-xs bg-white" required accept="image/*">
                                    <button type="submit" class="btn-secondary w-full py-3 font-bold">UNGGAH BUKTI MANUAL</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @if($rental->payment_status === 'paid' && $rental->end_date->isPast())
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    @if($rental->reviews()->where('user_id', auth()->id())->exists())
                        <div class="p-4 bg-emerald-50 rounded-xl text-center border border-emerald-100">
                            <p class="text-emerald-700 text-sm font-medium">✨ Terima kasih! Anda sudah memberikan ulasan untuk sewa bus ini.</p>
                        </div>
                    @else
                        <button onclick="openReviewModal('rental', {{ $rental->id }})" class="btn-primary w-full py-4 font-bold flex items-center justify-center gap-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                             BERI ULASAN SEWA
                        </button>
                    @endif
                </div>
            @endif

            @elseif($rental->approval_status === 'pending')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100 text-center">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-bold text-amber-800 mb-1">Menunggu Persetujuan Admin</h4>
                        <p class="text-sm text-amber-600">Admin sedang memproses permintaan sewa bus Anda. Harap menunggu konfirmasi.</p>
                    </div>
                </div>
            @elseif($rental->approval_status === 'rejected')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-red-50 rounded-2xl border border-red-100 text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h4 class="font-bold text-red-800 mb-1">Permintaan Ditolak</h4>
                        <p class="text-sm text-red-600">Maaf, permintaan sewa bus Anda tidak dapat diproses.</p>
                    </div>
                </div>
            @elseif($rental->payment_status === 'paid')
                <div class="mt-8 pt-8 border-t border-gray-warm-100">
                    <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h4 class="font-bold text-emerald-800 mb-1">Pembayaran Lunas</h4>
                        <p class="text-sm text-emerald-600">Terima kasih! Pembayaran Anda telah dikonfirmasi. Bus siap melayani perjalanan Anda.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('partials.review-modal')
@endpush
