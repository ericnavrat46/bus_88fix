<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ ucfirst($type) }} - Bus 88</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #fff;
            padding: 32px 40px;
        }

        /* Header */
        .report-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #cc0000;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .company-logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .logo-badge {
            width: 52px; height: 52px;
            background: #cc0000;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 900; font-size: 20px;
        }
        .company-name { font-size: 22px; font-weight: 900; color: #cc0000; }
        .company-sub  { font-size: 11px; color: #888; margin-top: 2px; }
        .report-meta  { text-align: right; }
        .report-title { font-size: 18px; font-weight: 800; color: #1a1a1a; }
        .report-period { font-size: 11px; color: #666; margin-top: 4px; }
        .print-date   { font-size: 10px; color: #aaa; margin-top: 2px; }

        /* Summary */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        .summary-card {
            border: 1.5px solid #e5e1dc;
            border-radius: 10px;
            padding: 14px 16px;
            text-align: center;
        }
        .summary-card.accent { border-left: 4px solid #cc0000; }
        .summary-card.green  { border-left: 4px solid #10b981; }
        .summary-card.amber  { border-left: 4px solid #f59e0b; }
        .summary-card.red    { border-left: 4px solid #ef4444; }
        .summary-card.blue   { border-left: 4px solid #3b82f6; }
        .summary-value { font-size: 26px; font-weight: 900; color: #1a1a1a; }
        .summary-label { font-size: 10px; color: #888; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.04em; }

        /* Revenue Banner */
        .revenue-banner {
            background: linear-gradient(135deg, #cc0000, #7f1d1d);
            color: white;
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .revenue-label  { font-size: 11px; opacity: 0.75; margin-bottom: 4px; }
        .revenue-amount { font-size: 28px; font-weight: 900; }
        .revenue-period { font-size: 10px; opacity: 0.6; margin-top: 3px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #cc0000; color: white; }
        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        tbody tr { border-bottom: 1px solid #f0ece8; }
        tbody tr:nth-child(even) { background: #faf9f8; }
        tbody td { padding: 9px 12px; font-size: 11px; vertical-align: top; }
        .td-code    { font-weight: 700; font-size: 10px; letter-spacing: 0.08em; color: #cc0000; }
        .td-name    { font-weight: 600; }
        .td-email   { font-size: 10px; color: #999; }
        .td-amount  { font-weight: 700; color: #cc0000; }
        .td-num     { color: #bbb; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-paid      { background: #d1fae5; color: #065f46; }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-refunded  { background: #dbeafe; color: #1e40af; }
        .badge-gray      { background: #f3ede8; color: #666; }
        .badge-approved  { background: #d1fae5; color: #065f46; }
        .badge-rejected  { background: #fee2e2; color: #991b1b; }

        /* Footer */
        .report-footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e5e1dc;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 10px;
            color: #aaa;
        }
        .sign-block { text-align: center; }
        .sign-line  {
            width: 160px;
            border-top: 1.5px solid #666;
            margin-top: 48px;
            margin-bottom: 4px;
        }
        .sign-name  { font-weight: 700; font-size: 11px; color: #333; }
        .sign-role  { font-size: 10px; color: #888; }

        @media print {
            body { padding: 16px 20px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    {{-- Print Button (hidden when printing) --}}
    <div class="no-print" style="text-align:right; margin-bottom:20px;">
        <button onclick="window.print()"
            style="background:#cc0000; color:white; border:none; padding:10px 24px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
            style="background:#f3ede8; color:#666; border:none; padding:10px 20px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; margin-left:8px;">
            Tutup
        </button>
    </div>

    {{-- Header --}}
    <div class="report-header">
        <div class="company-logo">
            <div class="logo-badge">88</div>
            <div>
                <div class="company-name">Bus 88</div>
                <div class="company-sub">PT Bus 88 Merah Putih · Jasa Transportasi & Wisata</div>
            </div>
        </div>
        <div class="report-meta">
            <div class="report-title">
                LAPORAN
                @if($type === 'booking') BOOKING TIKET
                @elseif($type === 'rental') SEWA / CHARTER BUS
                @else PAKET WISATA
                @endif
            </div>
            <div class="report-period">
                Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
                – {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
            </div>
            <div class="print-date">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary-grid">
        <div class="summary-card accent">
            <div class="summary-value">{{ $summary['total'] }}</div>
            <div class="summary-label">Total Transaksi</div>
        </div>

        @if($type === 'booking')
        <div class="summary-card green">
            <div class="summary-value">{{ $summary['paid'] }}</div>
            <div class="summary-label">Lunas</div>
        </div>
        <div class="summary-card amber">
            <div class="summary-value">{{ $summary['pending'] }}</div>
            <div class="summary-label">Menunggu</div>
        </div>
        <div class="summary-card red">
            <div class="summary-value">{{ $summary['cancelled'] }}</div>
            <div class="summary-label">Batal / Expired</div>
        </div>

        @elseif($type === 'rental')
        <div class="summary-card green">
            <div class="summary-value">{{ $summary['approved'] }}</div>
            <div class="summary-label">Disetujui</div>
        </div>
        <div class="summary-card amber">
            <div class="summary-value">{{ $summary['pending'] }}</div>
            <div class="summary-label">Menunggu</div>
        </div>
        <div class="summary-card red">
            <div class="summary-value">{{ $summary['rejected'] }}</div>
            <div class="summary-label">Ditolak</div>
        </div>

        @elseif($type === 'tour')
        <div class="summary-card green">
            <div class="summary-value">{{ $summary['paid'] }}</div>
            <div class="summary-label">Lunas</div>
        </div>
        <div class="summary-card amber">
            <div class="summary-value">{{ $summary['pending'] }}</div>
            <div class="summary-label">Menunggu</div>
        </div>
        <div class="summary-card blue">
            <div class="summary-value">{{ $data->count() }}</div>
            <div class="summary-label">Total Peserta</div>
        </div>
        @endif
    </div>

    {{-- Revenue Banner --}}
    <div class="revenue-banner">
        <div>
            <div class="revenue-label">Total Pendapatan (Lunas)</div>
            <div class="revenue-amount">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</div>
            <div class="revenue-period">
                {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} –
                {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
            </div>
        </div>
        <div style="opacity:0.15; font-size:64px; font-weight:900; line-height:1;">Rp</div>
    </div>

    {{-- Data Table --}}
    @if($type === 'booking')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Customer</th>
                <th>Rute</th>
                <th>Tgl Keberangkatan</th>
                <th>Kursi</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tgl Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $booking)
            <tr>
                <td class="td-num">{{ $loop->iteration }}</td>
                <td class="td-code">{{ $booking->booking_code }}</td>
                <td>
                    <div class="td-name">{{ $booking->user->name }}</div>
                    <div class="td-email">{{ $booking->user->email }}</div>
                </td>
                <td>{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</td>
                <td>{{ $booking->schedule->departure_date->format('d/m/Y') }}</td>
                <td>{{ $booking->total_seats }}</td>
                <td class="td-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>
                    @php $s = $booking->payment_status; @endphp
                    <span class="badge {{ $s === 'paid' ? 'badge-paid' : ($s === 'pending' ? 'badge-pending' : ($s === 'refunded' ? 'badge-refunded' : 'badge-cancelled')) }}">
                        {{ ucfirst($s) }}
                    </span>
                </td>
                <td>{{ $booking->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding:24px; color:#aaa;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    @elseif($type === 'rental')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Customer</th>
                <th>Rute</th>
                <th>Periode</th>
                <th>Bus</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tgl Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $rental)
            <tr>
                <td class="td-num">{{ $loop->iteration }}</td>
                <td class="td-code">{{ $rental->rental_code }}</td>
                <td>
                    <div class="td-name">{{ $rental->user->name }}</div>
                    <div class="td-email">{{ $rental->user->email }}</div>
                </td>
                <td>{{ $rental->pickup_location }} → {{ $rental->destination }}</td>
                <td>{{ $rental->start_date->format('d/m/Y') }} – {{ $rental->end_date->format('d/m/Y') }}</td>
                <td>{{ $rental->bus?->name ?? '-' }}</td>
                <td class="td-amount">{{ $rental->total_price ? 'Rp ' . number_format($rental->total_price, 0, ',', '.') : '-' }}</td>
                <td>
                    @php $s = $rental->approval_status; @endphp
                    <span class="badge {{ $s === 'approved' ? 'badge-approved' : ($s === 'pending' ? 'badge-pending' : 'badge-rejected') }}">
                        {{ ucfirst($s) }}
                    </span>
                </td>
                <td>{{ $rental->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding:24px; color:#aaa;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    @elseif($type === 'tour')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Customer</th>
                <th>Paket Wisata</th>
                <th>Tgl Wisata</th>
                <th>Peserta</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tgl Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $booking)
            <tr>
                <td class="td-num">{{ $loop->iteration }}</td>
                <td class="td-code">{{ $booking->booking_code }}</td>
                <td>
                    <div class="td-name">{{ $booking->user->name }}</div>
                    <div class="td-email">{{ $booking->user->email }}</div>
                </td>
                <td>{{ $booking->tourPackage?->name ?? '-' }}</td>
                <td>{{ $booking->travel_date?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $booking->passenger_count }}</td>
                <td class="td-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>
                    @php $s = $booking->payment_status; @endphp
                    <span class="badge {{ $s === 'paid' ? 'badge-paid' : ($s === 'pending' ? 'badge-pending' : 'badge-gray') }}">
                        {{ ucfirst($s) }}
                    </span>
                </td>
                <td>{{ $booking->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding:24px; color:#aaa;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif

    {{-- Footer --}}
    <div class="report-footer">
        <div>
            <div>Dokumen ini digenerate secara otomatis oleh sistem Bus 88.</div>
            <div style="margin-top:4px;">Total data: {{ $data->count() }} baris</div>
        </div>
        <div class="sign-block">
            <div class="sign-line"></div>
            <div class="sign-name">Administrator</div>
            <div class="sign-role">Bus 88</div>
        </div>
    </div>

    <script>
        // Auto trigger print dialog on load (opsional)
        // window.onload = () => window.print();
    </script>
</body>
</html>
