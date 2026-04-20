<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>E-Ticket Wisata - {{ $booking->booking_code }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif; background:#f4f6f8; color:#1a1a1a; font-size:12px; }
.page { width:794px; min-height:1123px; margin:0 auto; padding:28px; background:#f4f6f8; }
.ticket-wrap { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,0.10); }

/* HEADER — hijau untuk tour */
.header { background:#1b5e20; padding:20px 28px 18px; color:#fff; display:flex; justify-content:space-between; align-items:flex-start; }
.brand-name    { font-size:22px; font-weight:900; letter-spacing:-0.5px; }
.brand-tagline { font-size:10px; opacity:0.85; letter-spacing:0.3px; }
.booking-label { font-size:9px; opacity:0.8; text-transform:uppercase; letter-spacing:1px; }
.booking-code  { font-size:14px; font-weight:900; letter-spacing:2px; margin-top:2px; }
.badge-paid    { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; border-radius:4px; padding:3px 10px; font-size:10px; font-weight:700; }

/* PACKAGE BANNER */
.pkg-banner { background:#e8f5e9; border-bottom:2px solid #4caf50; padding:16px 28px; }
.pkg-name   { font-size:20px; font-weight:900; color:#1b5e20; margin-bottom:3px; }
.pkg-desc   { font-size:11px; color:#555; }
.pkg-meta   { display:flex; gap:20px; margin-top:8px; }
.pkg-meta-item { font-size:10px; color:#388e3c; font-weight:600; }

/* ALERT */
.alert-strip { background:#f1f8e9; border-top:2px solid #8bc34a; padding:8px 28px; font-size:10px; color:#33691e; font-weight:600; }

/* BODY */
.body { padding:20px 28px; display:flex; gap:24px; }
.body-left  { flex:1; }
.body-right { width:200px; flex-shrink:0; display:flex; flex-direction:column; align-items:center; gap:6px; padding-left:20px; border-left:1px dashed #ddd; }

/* SECTION */
.section { margin-bottom:18px; }
.section-head  { display:flex; align-items:center; gap:8px; margin-bottom:10px; padding-bottom:6px; border-bottom:1.5px solid #f0f0f0; }
.section-icon  { font-size:14px; }
.section-title { font-size:11px; font-weight:700; color:#1b5e20; text-transform:uppercase; letter-spacing:0.8px; }

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

/* PRICE BOX */
.price-box { background:#e8f5e9; border-radius:8px; padding:12px; text-align:center; margin-bottom:10px; }
.price-label { font-size:10px; color:#388e3c; margin-bottom:4px; }
.price-big   { font-size:20px; font-weight:900; color:#1b5e20; }
.price-per   { font-size:10px; color:#555; margin-top:3px; }

/* NOTICE */
.notice { background:#f1f8e9; border-left:3px solid #4caf50; border-radius:4px; padding:8px 12px; font-size:10px; color:#33691e; line-height:1.6; }

/* QR */
.qr-label-top { font-size:9px; font-weight:700; color:#444; text-transform:uppercase; letter-spacing:1px; text-align:center; }
.qr-img { width:160px; height:160px; }
.qr-code-text { font-size:10px; font-weight:700; color:#1b5e20; text-align:center; letter-spacing:1px; font-family:'Courier New',monospace; }
.qr-hint  { font-size:9px; color:#999; text-align:center; line-height:1.5; }
.qr-divider { width:100%; border:none; border-top:1px dashed #e0e0e0; margin:4px 0; }
.qr-price-label { font-size:9px; color:#888; text-align:center; }
.qr-price-value { font-size:16px; font-weight:900; color:#1b5e20; text-align:center; }

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
            <div class="brand-name">BUS 88 WISATA</div>
            <div class="brand-tagline">E-Ticket Paket Wisata &bull; Tour Package Ticket</div>
        </div>
        <div style="text-align:right;">
            <div class="booking-label">Kode Booking</div>
            <div class="booking-code">{{ $booking->booking_code }}</div>
            <div style="margin-top:6px;"><span class="badge-paid">&#10003; LUNAS</span></div>
        </div>
    </div>

    <div class="pkg-banner">
        <div class="pkg-name">{{ $booking->tourPackage->name ?? 'Paket Wisata' }}</div>
        @if($booking->tourPackage && $booking->tourPackage->description)
        <div class="pkg-desc">{{ $booking->tourPackage->description }}</div>
        @endif
        <div class="pkg-meta">
            @if($booking->travel_date)
            <div class="pkg-meta-item">&#128197; {{ \Carbon\Carbon::parse($booking->travel_date)->translatedFormat('d F Y') }}</div>
            @endif
            <div class="pkg-meta-item">&#128101; {{ $booking->passenger_count }} Peserta</div>
            @if($booking->tourPackage && $booking->tourPackage->duration)
            <div class="pkg-meta-item">&#9200; {{ $booking->tourPackage->duration }}</div>
            @endif
        </div>
    </div>

    <div class="alert-strip">
        &#9888; Hadir di titik kumpul minimal 30 menit sebelum jadwal. Bawa tiket ini beserta identitas diri.
    </div>

    <div class="body">

        <div class="body-left">

            <div class="section">
                <div class="section-head">
                    <span class="section-icon">&#128203;</span>
                    <span class="section-title">Informasi Pemesan</span>
                </div>
                <div class="info-grid">
                    <div>
                        <div class="info-label">Nama Pemesan</div>
                        <div class="info-value">{{ $booking->user->name }}</div>
                    </div>
                    <div>
                        <div class="info-label">No. Telepon</div>
                        <div class="info-value">{{ $booking->user->phone ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="info-label">Email</div>
                        <div class="info-value" style="font-size:11px;">{{ $booking->user->email }}</div>
                    </div>
                    <div>
                        <div class="info-label">Tanggal Pesan</div>
                        <div class="info-value">{{ $booking->created_at->translatedFormat('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-head">
                    <span class="section-icon">&#127758;</span>
                    <span class="section-title">Detail Paket Wisata</span>
                </div>
                <table class="detail-table">
                    <tr><td>Nama Paket</td><td>{{ $booking->tourPackage->name ?? '-' }}</td></tr>
                    <tr><td>Tanggal Wisata</td><td>{{ $booking->travel_date ? \Carbon\Carbon::parse($booking->travel_date)->translatedFormat('l, d F Y') : '-' }}</td></tr>
                    <tr><td>Jumlah Peserta</td><td>{{ $booking->passenger_count }} orang</td></tr>
                    @if($booking->tourPackage && $booking->tourPackage->duration)
                    <tr><td>Durasi</td><td>{{ $booking->tourPackage->duration }}</td></tr>
                    @endif
                    @if($booking->tourPackage && $booking->tourPackage->meeting_point)
                    <tr><td>Titik Kumpul</td><td>{{ $booking->tourPackage->meeting_point }}</td></tr>
                    @endif
                    @if($booking->tourPackage && $booking->tourPackage->includes)
                    <tr><td>Termasuk</td><td>{{ $booking->tourPackage->includes }}</td></tr>
                    @endif
                </table>
            </div>

            <div class="price-box">
                <div class="price-label">Total Harga Paket</div>
                <div class="price-big">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                <div class="price-per">
                    Rp {{ number_format($booking->total_price / max($booking->passenger_count, 1), 0, ',', '.') }}
                    / orang &times; {{ $booking->passenger_count }} peserta
                </div>
            </div>

            <div class="notice">
                <strong>Informasi Penting:</strong> Hadir di titik kumpul tepat waktu sesuai jadwal.
                Bawa tiket ini (cetak/digital) beserta identitas diri yang masih berlaku.
                Informasi lebih lanjut: <strong>cs@bus88.co.id</strong>
            </div>

        </div>

        <div class="body-right">
            <div class="qr-label-top">Scan Untuk Verifikasi</div>
            @if($qrCode)
                <img class="qr-img" src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            @else
                <div style="width:160px;height:160px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:10px;color:#aaa;text-align:center;border:1px solid #e0e0e0;border-radius:4px;">QR tidak tersedia</div>
            @endif
            <div class="qr-code-text">{{ $booking->booking_code }}</div>
            <div class="qr-hint">Scan QR Code ini<br>untuk memverifikasi<br>keaslian tiket Anda</div>
            <hr class="qr-divider">
            <div class="qr-price-label">Total Harga</div>
            <div class="qr-price-value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
            <hr class="qr-divider">
            <div style="font-size:9px;color:#999;text-align:center;line-height:1.6;">
                Pembayaran diterima<br>{{ $booking->updated_at->translatedFormat('d M Y H:i') }}<br>
                Dicetak: {{ now()->translatedFormat('d M Y H:i') }}
            </div>
        </div>

    </div>

    <div class="footer">
        <div class="footer-left">
            BUS 88 WISATA &mdash; Jelajahi Nusantara Bersama Kami<br>
            Dokumen ini sah tanpa tanda tangan basah &bull; Berlaku sesuai tanggal wisata
        </div>
        <div style="text-align:right;">
            <div class="footer-price-label">Total Pembayaran</div>
            <div class="footer-price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
        </div>
    </div>

</div>
<div class="watermark">{{ $booking->booking_code }} &bull; BUS 88 E-Ticket Wisata &bull; {{ now()->format('Y') }}</div>
</div>
</body>
</html>