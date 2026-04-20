<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi Tiket - Bus 88</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f4f6f8;
    min-height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 32px 16px;
}

.wrapper { width: 100%; max-width: 480px; }

/* BRAND */
.brand { text-align:center; margin-bottom:24px; }
.brand-logo { font-size:28px; font-weight:900; color:#cc0000; letter-spacing:-1px; }
.brand-sub  { font-size:12px; color:#888; margin-top:4px; }

/* CARD */
.card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.10); }

/* STATUS HEADER */
.status-header {
    padding: 28px 24px 20px;
    text-align: center;
}
.status-header.valid   { background: linear-gradient(135deg, #2e7d32, #43a047); }
.status-header.invalid { background: linear-gradient(135deg, #b71c1c, #e53935); }

.status-circle {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    margin: 0 auto 12px;
    color: #fff;
}

.status-title {
    font-size: 20px;
    font-weight: 900;
    color: #fff;
    margin-bottom: 4px;
}

.status-subtitle {
    font-size: 12px;
    color: rgba(255,255,255,0.85);
}

/* TODAY BADGE */
.today-wrap { padding: 12px 20px; }

.badge-today {
    display: block;
    background: #e8f5e9;
    border: 1.5px solid #66bb6a;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #2e7d32;
    text-align: center;
}

.badge-not-today {
    display: block;
    background: #fff8e1;
    border: 1.5px solid #ffc107;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #e65100;
    text-align: center;
}

/* DATA TABLE */
.data-section { padding: 0 20px 16px; }

.data-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 0 8px;
    font-size: 10px;
    font-weight: 700;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 2px;
}

.data-row {
    display: flex;
    padding: 9px 0;
    border-bottom: 1px solid #f8f8f8;
    gap: 8px;
}
.data-row:last-child { border-bottom: none; }

.data-label {
    color: #888;
    font-size: 11px;
    width: 44%;
    flex-shrink: 0;
    line-height: 1.4;
}
.data-value {
    font-weight: 700;
    font-size: 12px;
    color: #1a1a1a;
    flex: 1;
    line-height: 1.4;
    word-break: break-word;
}

.value-paid {
    background: #e8f5e9;
    color: #2e7d32;
    border-radius: 4px;
    padding: 2px 8px;
    font-size: 10px;
    font-weight: 700;
    display: inline-block;
}

/* FOOTER */
.card-footer {
    background: #f8f9fa;
    border-top: 1px solid #f0f0f0;
    padding: 14px 20px;
    font-size: 10px;
    color: #aaa;
    text-align: center;
    line-height: 1.6;
}

.btn-back {
    display: block;
    margin: 16px 20px;
    padding: 12px;
    background: #cc0000;
    color: #fff;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
}
.btn-back:hover { background: #990000; }
</style>
</head>
<body>
<div class="wrapper">

    <div class="brand">
        <div class="brand-logo">BUS 88</div>
        <div class="brand-sub">Sistem Verifikasi Tiket Elektronik</div>
    </div>

    <div class="card">

        {{-- STATUS HEADER --}}
        <div class="status-header {{ $valid ? 'valid' : 'invalid' }}">
            <div class="status-circle">
                {!! $valid ? '&#10003;' : '&#10007;' !!}
            </div>
            <div class="status-title">{{ $valid ? 'Tiket Valid' : 'Tiket Tidak Valid' }}</div>
            <div class="status-subtitle">{{ $message }}</div>
        </div>

        @if($valid && isset($data))

            {{-- TODAY/NOT TODAY BADGE --}}
            @if(isset($is_today))
            <div class="today-wrap">
                @if($is_today)
                    <span class="badge-today">&#10003; Jadwal Hari Ini — Penumpang Boleh Naik</span>
                @else
                    <span class="badge-not-today">&#9888; Jadwal Bukan Hari Ini — Harap Periksa Ulang</span>
                @endif
            </div>
            @endif

            {{-- DATA --}}
            <div class="data-section">
                <div class="data-header">
                    @php
                        $typeLabel = match($type ?? 'bus') {
                            'rental' => '🚌 Detail Sewa Bus',
                            'tour'   => '🌍 Detail Paket Wisata',
                            default  => '🎫 Detail Tiket Bus',
                        };
                    @endphp
                    {{ $typeLabel }}
                </div>

                @foreach($data as $label => $value)
                    <div class="data-row">
                        <span class="data-label">{{ $label }}</span>
                        <span class="data-value">
                            @if($label === 'Status Bayar')
                                <span class="value-paid">&#10003; {{ $value }}</span>
                            @else
                                {{ $value }}
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>

        @endif

        <div class="card-footer">
            Diverifikasi pada {{ now()->translatedFormat('d F Y, H:i:s') }} WIB<br>
            Bus 88 &bull; Sistem Tiket Elektronik
        </div>

        <a href="{{ route('admin.ticket.scan') }}" class="btn-back">
            &#8592; Kembali ke Halaman Scan
        </a>

    </div>

</div>
</body>
</html>