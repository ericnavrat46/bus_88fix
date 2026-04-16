@extends('layouts.admin')
@section('page-title', 'Sewa / Charter')
@section('content')
<p class="text-gray-warm-500 mb-6">Monitor dan kelola pengajuan sewa bus</p>
<div class="space-y-4">
    @foreach($rentals as $rental)
    <div class="card p-6" x-data="{ showAction: false }">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-sm font-bold text-merah-600 tracking-wider">{{ $rental->rental_code }}</span>
                    <span class="{{ match($rental->approval_status) { 'approved' => 'badge-success', 'pending' => 'badge-warning', default => 'badge-danger' } }}">{{ ucfirst($rental->approval_status) }}</span>
                    @if($rental->payment_status !== 'unpaid')
                    <span class="{{ match($rental->payment_status) { 'paid' => 'badge-success', 'pending' => 'badge-warning', default => 'badge-gray' } }}">Bayar: {{ $rental->payment_status }}</span>
                    @endif
                </div>
                <p class="font-medium text-dark">{{ $rental->user->name }} <span class="text-gray-warm-400">•</span> {{ $rental->contact_phone }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-sm text-gray-warm-500">{{ $rental->pickup_location }} → {{ $rental->destination }}</p>
                    <div class="flex items-center gap-1">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($rental->pickup_location) }}" target="_blank" class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Buka di Google Maps">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </a>
                        <button onclick="copyToClipboard('{{ $rental->pickup_location }}')" class="p-1 text-gray-warm-500 hover:bg-gray-warm-100 rounded transition-colors" title="Salin Lokasi">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        </button>
                    </div>
                </div>
                <p class="text-sm text-gray-warm-500">{{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }} ({{ $rental->duration_days }} hari)</p>
                @if($rental->purpose)<p class="text-sm text-gray-warm-400">Tujuan: {{ $rental->purpose }}</p>@endif
                @if($rental->passenger_count)<p class="text-sm text-gray-warm-400">Penumpang: ~{{ $rental->passenger_count }} orang</p>@endif
            </div>
            <div class="text-right flex flex-col items-end gap-2">
                @if($rental->total_price)
                <p class="text-xl font-black text-merah-600">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                @endif
                
                <div class="flex gap-2">
                    @if($rental->approval_status === 'pending')
                    <button @click="showAction = !showAction" class="btn-primary btn-sm">Proses</button>
                    @endif

                    @if($rental->payment_proof && $rental->payment_status !== 'paid')
                    <div x-data="{ modal: false }">
                        <button @click="modal = true" class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold hover:bg-emerald-200 transition-colors">
                            Lihat Bukti Bayar
                        </button>
                        
                        {{-- Modal Bukti --}}
                        <div x-show="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-dark/50 backdrop-blur-sm text-left" style="display: none;">
                            <div @click.away="modal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="font-bold text-dark text-lg">Bukti Sewa #{{ $rental->rental_code }}</h3>
                                    <button @click="modal = false" class="text-gray-warm-400 hover:text-dark text-xl font-black">✕</button>
                                </div>
                                <img src="{{ asset('storage/' . $rental->payment_proof) }}" class="w-full rounded-xl mb-6 shadow-lg border border-gray-warm-100 max-h-[60vh] object-contain">
                                <div class="flex gap-3">
                                    <form method="POST" action="{{ route('admin.rental.approve-manual', $rental) }}" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full btn-primary py-3 font-bold">SETUJUI PEMBAYARAN</button>
                                    </form>
                                    <button @click="modal = false" class="flex-1 btn-secondary py-3 font-bold">TUTUP</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($rental->approval_status === 'pending')
        <div x-show="showAction" x-transition class="mt-6 pt-6 border-t border-gray-warm-100">
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Approve --}}
                <form method="POST" action="{{ route('admin.rental.approve', $rental) }}" class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    @csrf
                    <h4 class="font-bold text-emerald-700 mb-3">✓ Setujui</h4>
                    <div class="space-y-3">
                        <div><label class="label-field">Total Harga *</label><input type="number" name="total_price" class="input-field" required placeholder="Masukkan harga"></div>
                        <div><label class="label-field">Pilih Bus *</label>
                            <select name="bus_id" class="select-field" required>
                                <option value="">Pilih</option>
                                @foreach(\App\Models\Bus::where('status','active')->get() as $bus)
                                <option value="{{ $bus->id }}">{{ $bus->name }} ({{ $bus->capacity }} kursi)</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="label-field">Catatan</label><input type="text" name="admin_notes" class="input-field"></div>
                        <button type="submit" class="btn-success w-full">Setujui</button>
                    </div>
                </form>
                {{-- Reject --}}
                <form method="POST" action="{{ route('admin.rental.reject', $rental) }}" class="p-4 bg-red-50 rounded-xl border border-red-100">
                    @csrf
                    <h4 class="font-bold text-red-700 mb-3">✕ Tolak</h4>
                    <div class="space-y-3">
                        <div><label class="label-field">Alasan Penolakan</label><input type="text" name="admin_notes" class="input-field" placeholder="Opsional"></div>
                        <button type="submit" class="btn-danger w-full">Tolak Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>
<div class="mt-4">{{ $rentals->links() }}</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Lokasi berhasil disalin ke clipboard.',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    }
</script>
@endsection
