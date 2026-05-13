@extends('layouts.app')
@section('title', 'Ajukan Refund - Bus 88')
@section('content')
<div class="bg-gradient-to-b from-merah-50 to-cream min-h-screen py-10">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard.booking', $booking) }}" class="inline-flex items-center gap-1 text-sm text-merah-600 hover:underline mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Detail Booking
        </a>

        <div class="card p-8">
            <h1 class="text-2xl font-bold text-dark mb-2">Pengajuan Refund</h1>
            <p class="text-gray-warm-500 mb-8">Mohon lengkapi data berikut untuk memproses pengembalian dana Anda.</p>

            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-8 text-sm text-blue-800">
                <h4 class="font-bold mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Kebijakan Refund
                </h4>
                <ul class="list-disc list-inside space-y-1 opacity-80">
                    <li>> 24 jam sebelum berangkat: Refund 90%</li>
                    <li>6 - 24 jam sebelum berangkat: Refund 70%</li>
                    <li>< 6 jam sebelum berangkat: Tidak bisa refund</li>
                </ul>
            </div>

            <form action="{{ route('booking.refund', $booking) }}" method="POST" class="space-y-6">
                @csrf
                <div class="p-6 bg-gray-warm-50 rounded-2xl border border-gray-warm-100 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-warm-500">Estimasi Dana Kembali:</span>
                        <div class="text-right">
                            <span class="block text-2xl font-black text-merah-600">Rp {{ number_format($refundAmount, 0, ',', '.') }}</span>
                            <span class="text-xs text-merah-500 font-bold">({{ $refundPercentage }}% dari total bayar)</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label-field">Alasan Refund *</label>
                    <textarea name="reason" class="input-field min-h-[100px]" placeholder="Berikan alasan pembatalan Anda..." required></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <h3 class="font-bold text-dark text-sm mt-4 mb-2">Data Rekening Pengembalian</h3>
                    </div>
                    <div>
                        <label class="label-field">Nama Bank *</label>
                        <input type="text" name="bank_name" class="input-field" placeholder="Contoh: BCA, BRI, Mandiri" required>
                    </div>
                    <div>
                        <label class="label-field">Nomor Rekening *</label>
                        <input type="text" name="account_number" class="input-field" placeholder="Masukkan nomor rekening" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label-field">Nama Pemilik Rekening *</label>
                        <input type="text" name="account_name" class="input-field" placeholder="Sesuai buku tabungan" required>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="btn-primary w-full py-4 font-bold text-lg">AJUKAN REFUND SEKARANG</button>
                    <p class="text-center text-xs text-gray-warm-400 mt-4 italic">
                        Proses verifikasi refund membutuhkan waktu 1-3 hari kerja.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
