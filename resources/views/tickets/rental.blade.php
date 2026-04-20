<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>E-Ticket Sewa Bus - {{ $rental->rental_code }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif; background:#f4f6f8; color:#1a1a1a; font-size:12px; }
.page { width:794px; min-height:1123px; margin:0 auto; padding:28px; background:#f4f6f8; }
.ticket-wrap { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,0.10); }

/* HEADER — dark navy untuk rental */
.header { background:#1a237e; padding:20px 28px 18px; color:#fff; display:flex; justify-content:space-between; align-items:flex-start; }
.brand-name   { font-size:22px; font-weight:900; letter-spacing:-0.5px; }
.brand-tagline{ font-size:10px; opacity:0.85; letter-spacing:0.3px; }
.booking-label{ font-size:9px; opacity:0.8; text-transform:uppercase; letter-spacing:1px; }
.booking-code { font-size:14px; font-weight:900; letter-spacing:2px; margin-top:2px; }
.badge-paid   { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; border-radius:4px; padding:3px 10px; font-size:10px; font-weight:700; display:inline-flex; align-items:center; gap:4px; }

/* ROUTE BANNER */
.route-banner { background:#fff; border-bottom:1px solid #efefef; padding:16px 28px; display:flex; align-items:center; }
.city-name { font-size:18px; font-weight:900; color:#1a237e; margin-bottom:2px; }
.city-sub  { font-size:10px; color:#888; }
.route-mid { text-align:center; padding:0 20px; }
.mid-arrow { font-size:20px; color:#1a237e; }

/* ALERT */
.alert-strip { background:#e8eaf6; border-top:2px solid #3f51b5; padding:8px 28px; font-size:10px; color:#283593; font-weight:600; }

/* BODY */
.body { padding:20px 28px; display:flex; gap:24px; }
.body-left  { flex:1; }
.body-right { width:200px; flex-shrink:0; display:flex; flex-direction:column; align-items:center; gap:6px; padding-left:20px; border-left:1px dashed #ddd; }

/* SECTION */
.section { margin-bottom:18px; }
.section-head { display:flex; align-items:center; gap:8px; margin-bottom:10px; padding-bottom:6px; border-bottom:1.5px solid #f0f0f0; }
.section-icon  { font-size:14px; }
.section-title { font-size:11px; font-weight:700; color:#1a237e; text-transform:uppercase; letter-spacing:0.8px; }

/* JOURNEY */
.journey-detail { display:flex; gap:10px; padding:12px; background:#fafafa; border-radius:8px; border:1px solid #f0f0f0; }
.journey-timeline { display:flex; flex-direction:column; align-items:center; padding-top:4px; }
.jt-dot-top { width:10px; height:10px; border:2px solid #1a237e; border-radius:50%; background:#fff; flex-shrink:0; }
.jt-dot-bot { width:10px; height:10px; border:2px solid #1a237e; border-radius:50%; background:#1a237e; flex-shrink:0; }
.jt-line { width:2px; flex:1; background:repeating-linear-gradient(180deg,#1a237e 0,#1a237e 4px,transparent 4px,transparent 8px); min-height:30px; margin:3px 0; }
.journey-info { flex:1; display:flex; flex-direction:column; gap:14px; }
.jp-label   { font-size:9px; color:#999; text-transform:uppercase; letter-spacing:0.5px; }
.jp-place   { font-size:13px; font-weight:800; color:#1a1a1a; margin:1px 0; }
.jp-address { font-size:10px; color:#777; line-height:1.4; }
.jp-time    { font-size:11px; font-weight:700; color:#1a237e; margin-top:3px; }

/* INFO GRID */
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.info-label { font-size:9px; color:#999; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px; font-weight:600; }
.info-value { font-size:13px; font-weight:700; color:#1a1a1a; }

/* DETAIL TABLE */
.detail-table { width:100%; border-collapse:collapse; }
.detail-table tr { border-bottom:1px solid #f5f5f5; }
.detail-table tr:last-child { border-bottom:none; }
.detail-table td { padding:7px 10px; font-size:11px; vertical-align:top; }
.detail-table td:first-child { color:#888; width:40%; font-size:10px; }
.detail-table td:last-child { font-weight:700; color:#1a1a1a; }
.detail-table tr:nth-child(even) td { background:#fafafa; }

/* NOTICE */
.notice { background:#e8eaf6; border-left:3px solid #3f51b5; border-radius:4px; padding:8px 12px; font-size:10px; color:#283593; line-height:1.6; }

/* QR */
.qr-label-top { font-size:9px; font-weight:700; color:#444; text-transform:uppercase; letter-spacing:1px; text-align:center; }
.qr-img { width:160px; height:160px; }
.qr-code-text { font-size:10px; font-weight:700; color:#1a237e; text-align:center; letter-spacing:1px; font-family:'Courier New',monospace; }
.qr-hint  { font-size:9px; color:#999; text-align:center; line-height:1.5; }
.qr-divider { width:100%; border:none; border-top:1px dashed #e0e0e0; margin:4px 0; }
.qr-price-label { font-size:9px; color:#888; text-align:center; }
.qr-price-value { font-size:16px; font-weight:900; color:#1a237e; text-align:center; }

/* FOOTER */
.footer { background:#1a1a1a; padding:12px 28px; display:flex; justify-content:space-between; align-items:center; }
.footer-left  { color:#888; font-size:9px; line-height:1.6; }
.footer-price { font-size:18px; font-weight:900; color:#fff; }
.footer-price-label { font-size:9px; color:#888; text-align:right; }

.watermark { text-align:center; margin-top:12px; font-size:9px; color:#bbb; letter-spacing:1px; }
</style>
</head>
<body>
<div class="page">
<div class="ticket-wrap">

    <div class="header">
        <div>
            <div class="brand-name">BUS 88</div>
            <div class="brand-tagline">E-Ticket Sewa Bus &bull; Bus Charter Ticket</div>
        </div>
        <div style="text-align:right;">
            <div class="booking-label">Kode Sewa</div>
            <div class="booking-code">{{ $rental->rental_code }}</div>
            <div style="margin-top:6px;"><span class="badge-paid">&#10003; LUNAS</span></div>
        </div>
    </div>

    <div class="route-banner">
        <div style="flex:1;">
            <div class="city-name">{{ $rental->pickup_location }}</div>
            <div class="city-sub">Lokasi Penjemputan</div>
        </div>
        <div class="route-mid">
            <div class="mid-arrow">&#8594;</div>
            <div style="font-size:9px;color:#aaa;">{{ $rental->duration_days ?? '-' }} hari</div>
        </div>
        <div style="flex:1; text-align:right;">
            <div class="city-name">{{ $rental->destination }}</div>
            <div class="city-sub">Tujuan</div>
        </div>
    </div>

    <div class="alert-strip">
        &#9888; Tunjukkan tiket ini beserta identitas diri kepada petugas sebelum keberangkatan.
    </div>

    <div class="body">

        <div class="body-left">

            <div class="section">
                <div class="section-head">
                    <span class="section-icon">&#128652;</span>
                    <span class="section-title">Rincian Perjalanan</span>
                </div>
                <div class="journey-detail">
                    <div class="journey-timeline">
                        <div class="jt-dot-top"></div>
                        <div class="jt-line"></div>
                        <div class="jt-dot-bot"></div>
                    </div>
                    <div class="journey-info">
                        <div>
                            <div class="jp-label">Lokasi Penjemputan</div>
                            <div class="jp-place">{{ $rental->pickup_location }}</div>
                            <div class="jp-time">{{ \Carbon\Carbon::parse($rental->start_date)->translatedFormat('d F Y') }}</div>
                        </div>
                        <div>
                            <div class="jp-label">Tujuan</div>
                            <div class="jp-place">{{ $rental->destination }}</div>
                            <div class="jp-time">{{ \Carbon\Carbon::parse($rental->end_date)->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-head">
                    <span class="section-icon">&#128203;</span>
                    <span class="section-title">Informasi Penyewa</span>
                </div>
                <div class="info-grid">
                    <div>
                        <div class="info-label">Nama Penyewa</div>
                        <div class="info-value">{{ $rental->user->name }}</div>
                    </div>
                    <div>
                        <div class="info-label">No. Telepon</div>
                        <div class="info-value">{{ $rental->user->phone ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="info-label">Email</div>
                        <div class="info-value" style="font-size:11px;">{{ $rental->user->email }}</div>
                    </div>
                    <div>
                        <div class="info-label">Tanggal Pemesanan</div>
                        <div class="info-value">{{ $rental->created_at->translatedFormat('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-head">
                    <span class="section-icon">&#128664;</span>
                    <span class="section-title">Detail Armada &amp; Sewa</span>
                </div>
                <table class="detail-table">
                    <tr><td>Nama Armada</td><td>{{ $rental->bus->name ?? '-' }}</td></tr>
                    <tr><td>Kapasitas</td><td>{{ $rental->bus->capacity ?? '-' }} penumpang</td></tr>
                    <tr><td>Tanggal Mulai</td><td>{{ \Carbon\Carbon::parse($rental->start_date)->translatedFormat('d F Y') }}</td></tr>
                    <tr><td>Tanggal Selesai</td><td>{{ \Carbon\Carbon::parse($rental->end_date)->translatedFormat('d F Y') }}</td></tr>
                    <tr><td>Durasi Sewa</td><td>{{ $rental->duration_days ?? \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date)) + 1 }} hari</td></tr>
                    @if($rental->notes)
                    <tr><td>Catatan</td><td>{{ $rental->notes }}</td></tr>
                    @endif
                </table>
            </div>

            <div class="notice">
                <strong>Penting:</strong> Bus akan standby di lokasi penjemputan sesuai jadwal yang telah disepakati.
                Hubungi kami minimal H-1 jika ada perubahan jadwal. Tiket tidak dapat dipindahtangankan.
                CS: <strong>cs@bus88.co.id</strong>
            </div>

        </div>

        <div class="body-right">
            <div class="qr-label-top">Scan Untuk Verifikasi</div>
            @if($qrCode)
                <img class="qr-img" src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            @else
                <div style="width:160px;height:160px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:10px;color:#aaa;text-align:center;border:1px solid #e0e0e0;border-radius:4px;">QR tidak tersedia</div>
            @endif
            <div class="qr-code-text">{{ $rental->rental_code }}</div>
            <div class="qr-hint">Scan QR Code ini<br>untuk memverifikasi<br>keaslian tiket Anda</div>
            <hr class="qr-divider">
            <div class="qr-price-label">Total Harga Sewa</div>
            <div class="qr-price-value">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
            <hr class="qr-divider">
            <div style="font-size:9px;color:#999;text-align:center;line-height:1.6;">
                Pembayaran diterima<br>{{ $rental->updated_at->translatedFormat('d M Y H:i') }}<br>
                Dicetak: {{ now()->translatedFormat('d M Y H:i') }}
            </div>
        </div>

    </div>

    <div class="footer">
        <div class="footer-left">
            BUS 88 &mdash; Layanan Charter &amp; Sewa Bus Terpercaya<br>
            Dokumen ini sah tanpa tanda tangan basah &bull; Berlaku sesuai tanggal sewa
        </div>
        <div style="text-align:right;">
            <div class="footer-price-label">Total Pembayaran</div>
            <div class="footer-price">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
        </div>
    </div>

</div>
<div class="watermark">{{ $rental->rental_code }} &bull; BUS 88 E-Ticket Sewa &bull; {{ now()->format('Y') }}</div>
</div>
</body>
</html>