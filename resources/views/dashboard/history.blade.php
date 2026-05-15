@extends('layouts.app')
@section('title', $title . ' - Bus 88')

@push('styles')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-expired {
            background: #f1f5f9;
            color: #475569;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-red-600 transition-colors mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-3xl font-black text-slate-900">{{ $title }}</h1>
            <p class="text-slate-500 mt-2">Menampilkan semua riwayat pesanan Anda untuk kategori ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @forelse($items as $item)
                @php
                    // Map item to activity format for the partial
                    $activity = [
                        'id' => $item->booking_code ?? $item->rental_code,
                        'date' => $item->created_at,
                        'status' => $item->payment_status,
                        'url' => '#',
                        'icon' => '🎫',
                        'title' => 'Item',
                        'download_url' => null
                    ];

                    if ($type === 'bus') {
                        $activity['title'] = ($item->schedule->route->origin ?? 'N/A') . ' - ' . ($item->schedule->route->destination ?? 'N/A');
                        $activity['url'] = route('dashboard.booking', $item);
                        $activity['icon'] = '🎫';
                        $activity['download_url'] = $item->payment_status === 'paid' ? route('ticket.bus.download', $item) : null;
                    } elseif ($type === 'rental') {
                        $activity['title'] = $item->pickup_location . ' - ' . $item->destination;
                        $activity['url'] = route('dashboard.rental', $item);
                        $activity['icon'] = '🚌';
                        $activity['download_url'] = $item->payment_status === 'paid' ? route('ticket.rental.download', $item) : null;
                    } elseif ($type === 'tour') {
                        $activity['title'] = $item->tourPackage->name ?? 'Paket Tur';
                        $activity['url'] = route('dashboard.tour', $item);
                        $activity['icon'] = '🗺️';
                        $activity['download_url'] = $item->payment_status === 'paid' ? route('ticket.tour.download', $item) : null;
                    }
                @endphp

                @include('dashboard.partials.activity-item', ['activity' => $activity])
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-slate-300">
                    <div class="text-5xl mb-4">📭</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Belum ada riwayat</h3>
                    <p class="text-slate-500">Anda belum memiliki pesanan di kategori ini.</p>
                    <a href="{{ route('home') }}"
                        class="inline-block mt-6 px-8 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all">Cari
                        Sekarang</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $items->links() }}
        </div>
    </div>
@endsection