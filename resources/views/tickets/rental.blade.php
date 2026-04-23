<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>E-Ticket Sewa Bus - {{ $rental->rental_code }}</title>
<style>
    @page { margin: 0; }
    body { 
        font-family: Arial, sans-serif; 
        margin: 0; 
        padding: 20px; 
        background: #f4f6f8; 
        color: #1a1a1a; 
        font-size: 12px;
    }
    .ticket-wrap { 
        width: 100%; 
        background: #fff; 
        border-radius: 12px; 
        overflow: hidden; 
        border: 1px solid #ddd;
    }
    
    /* Layout Utama dengan Tabel */
    table { width: 100%; border-collapse: collapse; border: 0; }
    td { vertical-align: top; }

    /* HEADER */
    .header { 
        background: #1a237e; 
        padding: 20px 30px; 
        color: #fff; 
    }
    .brand-name { font-size: 24px; font-weight: bold; }
    .brand-tagline { font-size: 10px; opacity: 0.8; }
    .booking-label { font-size: 9px; opacity: 0.8; text-transform: uppercase; }
    .booking-code { font-size: 16px; font-weight: bold; letter-spacing: 1px; }
    .badge-paid { 
        background: #e8f5e9; 
        color: #2e7d32; 
        padding: 4px 12px; 
        border-radius: 4px; 
        font-size: 10px; 
        font-weight: bold; 
        margin-top: 5px;
        display: inline-block;
    }

    /* BANNER RUTE */
    .route-banner { 
        background: #fff; 
        padding: 20px 30px; 
        border-bottom: 1px solid #efefef;
    }
    .city-name { font-size: 18px; font-weight: bold; color: #1a237e; }
    .city-sub { font-size: 10px; color: #888; }
    .route-arrow { font-size: 24px; color: #1a237e; text-align: center; }

    /* ALERT */
    .alert-strip { 
        background: #e8eaf6; 
        border-top: 2px solid #3f51b5; 
        padding: 10px 30px; 
        font-size: 10px; 
        color: #283593; 
        font-weight: bold; 
    }

    /* CONTENT BODY */
    .body-container { padding: 30px; }
    .body-left { padding-right: 20px; border-right: 1px dashed #ddd; }
    .body-right { width: 220px; padding-left: 20px; text-align: center; }

    /* SECTION */
    .section-title { 
        font-size: 11px; 
        font-weight: bold; 
        color: #1a237e; 
        text-transform: uppercase; 
        border-bottom: 1.5px solid #f0f0f0; 
        padding-bottom: 5px;
        margin-bottom: 15px;
    }

    /* JOURNEY BOX */
    .journey-box { 
        background: #fafafa; 
        border: 1px solid #f0f0f0; 
        border-radius: 8px; 
        padding: 15px; 
        margin-bottom: 20px;
    }
    .jp-label { font-size: 9px; color: #999; text-transform: uppercase; }
    .jp-place { font-size: 13px; font-weight: bold; color: #1a1a1a; margin: 2px 0; }
    .jp-time { font-size: 11px; font-weight: bold; color: #1a237e; }

    /* INFO GRID (TABLE) */
    .info-table td { padding: 8px 0; }
    .info-label { font-size: 9px; color: #999; text-transform: uppercase; }
    .info-value { font-size: 12px; font-weight: bold; color: #1a1a1a; }

    /* DETAIL TABLE */
    .detail-table tr td { 
        padding: 8px 10px; 
        border-bottom: 1px solid #f5f5f5;
        font-size: 11px;
    }
    .detail-table td.label { color: #888; width: 40%; font-size: 10px; }
    .detail-table td.value { font-weight: bold; color: #1a1a1a; }

    /* NOTICE */
    .notice { 
        background: #e8eaf6; 
        border-left: 4px solid #3f51b5; 
        padding: 12px; 
        font-size: 10px; 
        color: #283593; 
        line-height: 1.5;
        margin-top: 20px;
    }

    /* QR SIDE */
    .qr-img { width: 160px; height: 160px; margin: 10px 0; }
    .qr-code-text { font-size: 12px; font-weight: bold; color: #1a237e; letter-spacing: 1px; }
    .qr-hint { font-size: 9px; color: #999; margin-top: 5px; }
    .price-box { 
        border-top: 1px dashed #ddd; 
        border-bottom: 1px dashed #ddd; 
        padding: 15px 0; 
        margin: 15px 0;
    }
    .price-label { font-size: 10px; color: #888; }
    .price-value { font-size: 18px; font-weight: bold; color: #1a237e; }

    /* FOOTER */
    .footer { background: #1a1a1a; padding: 15px 30px; color: #fff; }
    .footer-text { font-size: 9px; color: #aaa; }
    .footer-price { font-size: 20px; font-weight: bold; text-align: right; }

    .watermark { text-align: center; margin-top: 15px; font-size: 10px; color: #bbb; }
</style>
</head>
<body>

<div class="ticket-wrap">
    <!-- HEADER -->
    <table class="header">
        <tr>
            <td>
                <div class="brand-name">BUS 88</div>
                <div class="brand-tagline">E-Ticket Sewa Bus &bull; Bus Charter Ticket</div>
            </td>
            <td style="text-align:right;">
                <div class="booking-label">Kode Sewa</div>
                <div class="booking-code">{{ $rental->rental_code }}</div>
                <div class="badge-paid">LUNAS</div>
            </td>
        </tr>
    </table>

    <!-- ROUTE -->
    <table class="route-banner">
        <tr>
            <td width="40%">
                <div class="city-name">{{ $rental->pickup_location }}</div>
                <div class="city-sub">Lokasi Penjemputan</div>
            </td>
            <td width="20%" class="route-arrow">
                &rarr;
                <div style="font-size:10px; color:#aaa; font-weight:normal;">{{ $rental->duration_days ?? '-' }} Hari</div>
            </td>
            <td width="40%" style="text-align:right;">
                <div class="city-name">{{ $rental->destination }}</div>
                <div class="city-sub">Tujuan</div>
            </td>
        </tr>
    </table>

    <div class="alert-strip">
        PENTING: Tunjukkan e-tiket ini kepada pengemudi/petugas saat penjemputan armada.
    </div>

    <div class="body-container">
        <table>
            <tr>
                <td class="body-left">
                    <!-- Rincian Perjalanan -->
                    <div class="section-title">Rincian Perjalanan</div>
                    <table class="journey-box">
                        <tr>
                            <td style="padding-bottom:15px;">
                                <div class="jp-label">Penjemputan</div>
                                <div class="jp-place">{{ $rental->pickup_location }}</div>
                                <div class="jp-time">{{ \Carbon\Carbon::parse($rental->start_date)->translatedFormat('d F Y') }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="jp-label">Tujuan Utama</div>
                                <div class="jp-place">{{ $rental->destination }}</div>
                                <div class="jp-time">{{ \Carbon\Carbon::parse($rental->end_date)->translatedFormat('d F Y') }}</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Informasi Penyewa -->
                    <div class="section-title">Informasi Penyewa</div>
                    <table class="info-table">
                        <tr>
                            <td width="50%">
                                <div class="jp-label">Nama Penyewa</div>
                                <div class="info-value">{{ $rental->user->name }}</div>
                            </td>
                            <td width="50%">
                                <div class="jp-label">No. Telepon</div>
                                <div class="info-value">{{ $rental->contact_phone ?? $rental->user->phone ?? '-' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="jp-label">Email</div>
                                <div class="info-value">{{ $rental->user->email }}</div>
                            </td>
                            <td>
                                <div class="jp-label">Tgl Pemesanan</div>
                                <div class="info-value">{{ $rental->created_at->translatedFormat('d M Y') }}</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Detail Armada -->
                    <div style="margin-top:10px;" class="section-title">Detail Armada & Sewa</div>
                    <table class="detail-table">
                        <tr><td class="label">Nama Armada</td><td class="value">{{ $rental->bus->name ?? '-' }}</td></tr>
                        <tr><td class="label">Kapasitas</td><td class="value">{{ $rental->bus->capacity ?? '-' }} Kursi</td></tr>
                        <tr><td class="label">Durasi</td><td class="value">{{ $rental->duration_days }} Hari</td></tr>
                        @if($rental->purpose)
                        <tr><td class="label">Keperluan</td><td class="value">{{ $rental->purpose }}</td></tr>
                        @endif
                    </table>

                    <div class="notice">
                        <strong>Syarat & Ketentuan:</strong><br>
                        - Armada akan standby 30 menit sebelum waktu penjemputan.<br>
                        - Biaya sudah termasuk BBM dan Driver (Kecuali disepakati lain).<br>
                        - Hubungi CS di 0812-XXXX-XXXX jika ada kendala lapangan.
                    </div>
                </td>

                <td class="body-right">
                    <div style="font-size:10px; font-weight:bold; color:#666;">VERIFIKASI TIKET</div>
                    @if($qrCode)
                        <img class="qr-img" src="data:image/png;base64,{{ $qrCode }}">
                    @endif
                    <div class="qr-code-text">{{ $rental->rental_code }}</div>
                    <div class="qr-hint">Scan untuk cek keaslian</div>

                    <div class="price-box">
                        <div class="price-label">Total Harga Sewa</div>
                        <div class="price-value">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
                    </div>

                    <div style="font-size:9px; color:#999; line-height:1.4;">
                        Status: <strong>LUNAS</strong><br>
                        Dicetak pada: {{ now()->translatedFormat('d/m/Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <table class="footer">
        <tr>
            <td class="footer-text">
                <strong>BUS 88 - Layanan Sewa Bus Terpercaya</strong><br>
                Dokumen ini diterbitkan secara digital dan sah tanpa tanda tangan.
            </td>
            <td class="footer-price">
                <div style="font-size:9px; color:#aaa; font-weight:normal;">Total Pembayaran</div>
                Rp {{ number_format($rental->total_price, 0, ',', '.') }}
            </td>
        </tr>
    </table>
</div>

<div class="watermark">
    {{ $rental->rental_code }} &bull; BUS 88 E-TICKET &bull; {{ date('Y') }}
</div>

</body>
</html>