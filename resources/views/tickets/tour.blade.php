<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>E-Ticket Wisata - {{ $booking->booking_code }}</title>
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
    
    table { width: 100%; border-collapse: collapse; border: 0; }
    td { vertical-align: top; }

    /* HEADER - Green for Tour */
    .header { 
        background: #1b5e20; 
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

    /* PACKAGE BANNER */
    .pkg-banner { 
        background: #e8f5e9; 
        padding: 20px 30px; 
        border-bottom: 2px solid #4caf50;
    }
    .pkg-name { font-size: 20px; font-weight: bold; color: #1b5e20; }
    .pkg-desc { font-size: 11px; color: #555; margin-top: 4px; }

    /* ALERT */
    .alert-strip { 
        background: #f1f8e9; 
        border-top: 2px solid #8bc34a; 
        padding: 10px 30px; 
        font-size: 10px; 
        color: #33691e; 
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
        color: #1b5e20; 
        text-transform: uppercase; 
        border-bottom: 1.5px solid #f0f0f0; 
        padding-bottom: 5px;
        margin-bottom: 15px;
    }

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

    /* PRICE BOX */
    .price-box-wide { 
        background: #e8f5e9; 
        border-radius: 8px; 
        padding: 15px; 
        text-align: center; 
        margin: 20px 0;
    }
    .price-label { font-size: 10px; color: #388e3c; }
    .price-big { font-size: 22px; font-weight: bold; color: #1b5e20; }

    /* NOTICE */
    .notice { 
        background: #f1f8e9; 
        border-left: 4px solid #4caf50; 
        padding: 12px; 
        font-size: 10px; 
        color: #33691e; 
        line-height: 1.5;
        margin-top: 20px;
    }

    /* QR SIDE */
    .qr-img { width: 160px; height: 160px; margin: 10px 0; }
    .qr-code-text { font-size: 12px; font-weight: bold; color: #1b5e20; letter-spacing: 1px; }
    .qr-hint { font-size: 9px; color: #999; margin-top: 5px; }

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
                <div class="brand-name">BUS 88 WISATA</div>
                <div class="brand-tagline">E-Ticket Paket Wisata &bull; Tour Package Ticket</div>
            </td>
            <td style="text-align:right;">
                <div class="booking-label">Kode Booking</div>
                <div class="booking-code">{{ $booking->booking_code }}</div>
                <div class="badge-paid">LUNAS</div>
            </td>
        </tr>
    </table>

    <!-- PACKAGE BANNER -->
    <div class="pkg-banner">
        <div class="pkg-name">{{ $booking->tourPackage->name ?? 'Paket Wisata' }}</div>
        @if($booking->tourPackage && $booking->tourPackage->description)
            <div class="pkg-desc">{{ $booking->tourPackage->description }}</div>
        @endif
    </div>

    <div class="alert-strip">
        PENTING: Hadir di titik kumpul minimal 30 menit sebelum jadwal keberangkatan.
    </div>

    <div class="body-container">
        <table>
            <tr>
                <td class="body-left">
                    <!-- Informasi Pemesan -->
                    <div class="section-title">Informasi Pemesan</div>
                    <table class="info-table">
                        <tr>
                            <td width="50%">
                                <div class="info-label">Nama Pemesan</div>
                                <div class="info-value">{{ $booking->user->name }}</div>
                            </td>
                            <td width="50%">
                                <div class="info-label">No. Telepon</div>
                                <div class="info-value">{{ $booking->user->phone ?? '-' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $booking->user->email }}</div>
                            </td>
                            <td>
                                <div class="info-label">Tgl Pemesanan</div>
                                <div class="info-value">{{ $booking->created_at->translatedFormat('d M Y') }}</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Detail Paket -->
                    <div style="margin-top:10px;" class="section-title">Detail Paket Wisata</div>
                    <table class="detail-table">
                        <tr><td class="label">Nama Paket</td><td class="value">{{ $booking->tourPackage->name ?? '-' }}</td></tr>
                        <tr><td class="label">Tgl Wisata</td><td class="value">{{ \Carbon\Carbon::parse($booking->travel_date)->translatedFormat('l, d F Y') }}</td></tr>
                        <tr><td class="label">Jumlah Peserta</td><td class="value">{{ $booking->passenger_count }} Orang</td></tr>
                        @if($booking->tourPackage && $booking->tourPackage->duration)
                            <tr><td class="label">Durasi</td><td class="value">{{ $booking->tourPackage->duration }}</td></tr>
                        @endif
                        @if($booking->tourPackage && $booking->tourPackage->meeting_point)
                            <tr><td class="label">Titik Kumpul</td><td class="value">{{ $booking->tourPackage->meeting_point }}</td></tr>
                        @endif
                    </table>

                    <div class="price-box-wide">
                        <div class="price-label">Total Harga Paket Wisata</div>
                        <div class="price-big">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        <div style="font-size:10px; color:#555; margin-top:5px;">
                            (Rp {{ number_format($booking->total_price / max($booking->passenger_count, 1), 0, ',', '.') }} / orang)
                        </div>
                    </div>

                    <div class="notice">
                        <strong>Informasi Tambahan:</strong><br>
                        - Harap membawa kartu identitas yang masih berlaku.<br>
                        - Tiket ini berlaku sesuai dengan tanggal wisata yang tertera.<br>
                        - Hubungi admin jika membutuhkan bantuan penjemputan khusus.
                    </div>
                </td>

                <td class="body-right">
                    <div style="font-size:10px; font-weight:bold; color:#666;">VERIFIKASI TIKET</div>
                    @if($qrCode)
                        <img class="qr-img" src="data:image/png;base64,{{ $qrCode }}">
                    @endif
                    <div class="qr-code-text">{{ $booking->booking_code }}</div>
                    <div class="qr-hint">Scan untuk cek keaslian</div>

                    <div style="border-top:1px dashed #ddd; margin: 20px 0; padding-top:20px;">
                        <div style="font-size:9px; color:#999; line-height:1.6;">
                            Status Pembayaran:<br>
                            <strong style="color:#1b5e20; font-size:12px;">LUNAS / SETTLED</strong><br><br>
                            Waktu Cetak:<br>
                            {{ now()->translatedFormat('d/m/Y H:i') }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <table class="footer">
        <tr>
            <td class="footer-text">
                <strong>BUS 88 WISATA - Jelajahi Nusantara Bersama Kami</strong><br>
                Dokumen digital ini merupakan bukti pembayaran paket wisata yang sah.
            </td>
            <td class="footer-price">
                <div style="font-size:9px; color:#aaa; font-weight:normal;">Total Bayar</div>
                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </td>
        </tr>
    </table>
</div>

<div class="watermark">
    {{ $booking->booking_code }} &bull; BUS 88 TOUR E-TICKET &bull; {{ date('Y') }}
</div>

</body>
</html>