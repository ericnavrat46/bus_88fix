@extends('layouts.admin')
@section('page-title', 'Kelola Refund #' . $refund->booking->booking_code)
@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.transactions.refunds') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-merah-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Refund
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Detail Section --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-8">
                <h3 class="text-lg font-bold text-dark mb-6">Informasi Pengajuan</h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-4 bg-gray-warm-50 rounded-2xl border border-gray-warm-100">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Data Rekening</p>
                        <p class="text-sm font-bold text-dark">{{ $refund->bank_name }}</p>
                        <p class="text-lg font-black text-merah-600 tracking-tighter">{{ $refund->account_number }}</p>
                        <p class="text-xs text-gray-500 italic">a.n {{ $refund->account_name }}</p>
                    </div>

                    <div class="p-4 bg-merah-50 rounded-2xl border border-merah-100">
                        <p class="text-[10px] uppercase tracking-widest text-merah-400 font-bold mb-1">Jumlah Refund</p>
                        <p class="text-2xl font-black text-merah-600">Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-merah-400 font-medium italic mt-1">Estimasi pengembalian dana</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Alasan Pengguna</h4>
                    <div class="p-4 bg-white border border-gray-100 rounded-xl italic text-gray-600 text-sm leading-relaxed">
                        "{{ $refund->reason }}"
                    </div>
                </div>
            </div>

            <div class="card p-8">
                <h3 class="text-lg font-bold text-dark mb-6">Data Booking</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-400">Kode Booking</span>
                        <span class="font-bold text-dark">{{ $refund->booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-400">Customer</span>
                        <span class="font-bold text-dark">{{ $refund->user->name }} ({{ $refund->user->phone }})</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-400">Rute</span>
                        <span class="font-bold text-dark">{{ $refund->booking->schedule->route->origin }} → {{ $refund->booking->schedule->route->destination }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Bayar (Original)</span>
                        <span class="font-bold text-dark text-lg">Rp {{ number_format($refund->booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Section --}}
        <div>
            <div class="card p-8 sticky top-24">
                <h3 class="text-lg font-bold text-dark mb-6">Tindakan Admin</h3>
                
                <form action="{{ route('admin.refund.action', $refund) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Status Refund</label>
                        <select name="status" class="w-full text-sm border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-merah-500 outline-none bg-white">
                            <option value="pending" {{ $refund->status == 'pending' ? 'selected' : '' }}>🕒 Pending</option>
                            <option value="approved" {{ $refund->status == 'approved' ? 'selected' : '' }}>✅ Setujui (Proses)</option>
                            <option value="rejected" {{ $refund->status == 'rejected' ? 'selected' : '' }}>❌ Tolak</option>
                            <option value="completed" {{ $refund->status == 'completed' ? 'selected' : '' }}>💰 Selesai (Sudah Transfer)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Catatan Admin</label>
                        <textarea name="admin_notes" rows="4" class="w-full text-sm border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-merah-500 outline-none" placeholder="Masukkan catatan atau alasan penolakan...">{{ $refund->admin_notes }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-50">
                        <button type="submit" class="btn-primary w-full py-4 font-bold shadow-lg shadow-merah-600/20">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
