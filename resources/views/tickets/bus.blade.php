<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Tiket Ind's 88 Trans - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background: #e2e8f0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Ukuran tiket disesuaikan agar muat 1 halaman A4 */
        .ticket {
            max-width: 700px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            font-size: 13px;
            color: #1e293b;
        }

        /* Header kantor pusat */
        .header-office {
            background: #0f172a;
            padding: 20px 24px;
            color: white;
        }
        .office-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .office-address {
            font-size: 10px;
            opacity: 0.8;
            margin-top: 6px;
            line-height: 1.4;
        }

        /* Peringatan QR */
        .alert-qr {
            background: #fef9c3;
            padding: 10px 24px;
            font-size: 11px;
            font-weight: 700;
            color: #854d0e;
            border-bottom: 1px solid #fde047;
        }

        /* Contact bar */
        .contact-bar {
            background: #f1f5f9;
            padding: 8px 24px;
            font-size: 10px;
            color: #0f172a;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Rute utama */
        .route-row {
            display: flex;
            justify-content: space-between;
            padding: 24px 24px 16px;
            background: white;
        }
        .route-item {
            flex: 1;
        }
        .route-item.right {
            text-align: right;
        }
        .route-location {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 6px;
        }
        .route-datetime {
            font-size: 11px;
            color: #475569;
        }

        /* Kode booking & harga */
        .code-price {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            padding: 14px 24px;
            margin: 0 0 8px 0;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        .code, .price {
            font-weight: 800;
            font-size: 14px;
        }
        .price {
            color: #b91c1c;
        }

        /* Section */
        .section {
            padding: 16px 24px;
        }
        .section-title {
            font-weight: 800;
            font-size: 14px;
            color: #0f172a;
            border-left: 4px solid #b91c1c;
            padding-left: 10px;
            margin-bottom: 14px;
        }

        /* Detail perjalanan */
        .journey-detail {
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 4px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 12px;
        }
        .detail-label {
            width: 110px;
            font-weight: 600;
            color: #475569;
            font-size: 11px;
        }
        .detail-value {
            flex: 1;
            font-weight: 500;
            font-size: 12px;
        }
        .detail-value strong {
            font-weight: 800;
        }

        /* Info penumpang */
        .passenger-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px 16px;
        }
        .passenger-row {
            display: flex;
            margin-bottom: 10px;
        }
        .passenger-label {
            width: 110px;
            font-weight: 600;
            color: #475569;
            font-size: 11px;
        }
        .passenger-value {
            flex: 1;
            font-weight: 500;
            font-size: 12px;
        }

        /* QR Code */
        .qr-container {
            text-align: center;
            margin: 16px 24px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .qr-container img {
            width: 130px;
            height: 130px;
            margin: 0 auto;
        }
        .qr-text {
            font-size: 10px;
            color: #475569;
            margin-top: 8px;
        }

        /* Kontak & pembayaran */
        .contact-section {
            background: #f1f5f9;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .contact-section strong {
            font-size: 11px;
        }

        /* Footer */
        .print-footer {
            padding: 12px 24px;
            background: white;
            font-size: 9px;
            color: #64748b;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .ticket {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
            .alert-qr, .contact-bar, .contact-section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
<div class="ticket">
    <!-- Header Perusahaan -->
    <div class="header-office">
        <div class="office-name">INDI'S 88 TRANS</div>
        <div class="office-address">
            Jl. Brawijaya, Darungan, Jubung, Kec. Sukorambi, Kab. Jember, Jawa Timur
        </div>
    </div>

    <!-- Peringatan -->
    <div class="alert-qr">
        ⚠️ Penting: Kode QR wajib dipindai saat naik. Hadir 30 menit sebelum jadwal.
    </div>

    <!-- Kontak -->
    <div class="contact-bar">
        <span>📞 0822-3072-5758 / (0331) 3058888</span>
        <span>✉ cs@inds88trans.co.id</span>
        <span>📷 @inds88trans</span>
    </div>

    <!-- Rute -->
    <div class="route-row">
        <div class="route-item">
            <div class="route-location">{{ $booking->schedule->route->origin }}</div>
            <div class="route-datetime">
                {{ \Carbon\Carbon::parse($booking->schedule->departure_date)->translatedFormat('l, d F Y') }}<br>
                {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }} WIB
            </div>
        </div>
        <div class="route-item right">
            <div class="route-location">{{ $booking->schedule->route->destination }}</div>
            <div class="route-datetime">
                {{ \Carbon\Carbon::parse($booking->schedule->arrival_date)->translatedFormat('l, d F Y') }}<br>
                {{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }} WIB
            </div>
        </div>
    </div>

    <!-- Kode Booking & Harga -->
    <div class="code-price">
        <div class="code">Kode Booking: {{ $booking->booking_code }}</div>
        <div class="price">Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
    </div>

    <!-- Rincian Perjalanan -->
    <div class="section">
        <div class="section-title">RINCIAN PERJALANAN</div>
        <div class="journey-detail">
            <div class="detail-row">
                <div class="detail-label">Bus / Kelas</div>
                <div class="detail-value">
                    {{ $booking->schedule->bus->name ?? "INDI'S 88 TRANS" }} 
                    ({{ $booking->schedule->bus->bus_class ?? 'Eksekutif' }})
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Berangkat</div>
                <div class="detail-value">
                    <strong>{{ $booking->schedule->route->origin }}</strong><br>
                    {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }} WIB,
                    {{ \Carbon\Carbon::parse($booking->schedule->departure_date)->translatedFormat('l, d F Y') }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tiba di</div>
                <div class="detail-value">
                    <strong>{{ $booking->schedule->route->destination }}</strong><br>
                    {{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }} WIB,
                    {{ \Carbon\Carbon::parse($booking->schedule->arrival_date)->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Rincian Penumpang -->
    <div class="section">
        <div class="section-title">RINCIAN PENUMPANG</div>
        @php
            $firstPassenger = $booking->passengers->first();
        @endphp
        <div class="passenger-info">
            <div class="passenger-row">
                <div class="passenger-label">Nama Utama</div>
                <div class="passenger-value">{{ $firstPassenger->passenger_name ?? $booking->user->name ?? '-' }}</div>
            </div>
            <div class="passenger-row">
                <div class="passenger-label">Nomor Kursi</div>
                <div class="passenger-value">{{ $firstPassenger->seat_number ?? '-' }}</div>
            </div>
            <div class="passenger-row">
                <div class="passenger-label">No. Telepon</div>
                <div class="passenger-value">{{ $booking->user->phone ?? '-' }}</div>
            </div>
            <div class="passenger-row">
                <div class="passenger-label">Kode Tiket</div>
                <div class="passenger-value">{{ $booking->booking_code }}</div>
            </div>
        </div>
        @if($booking->passengers->count() > 1)
            <div style="margin-top: 10px; font-size: 11px; color: #b91c1c; background: #fee2e2; padding: 6px 12px; border-radius: 8px;">
                + {{ $booking->passengers->count() - 1 }} penumpang lainnya (detail lengkap di sistem)
            </div>
        @endif
    </div>

    <!-- QR Code -->
    <div class="qr-container">
        @if(!empty($qrCode))
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            <div class="qr-text">Scan QR ini untuk verifikasi tiket</div>
        @else
            <div class="qr-text">QR Code tidak tersedia</div>
        @endif
    </div>

    <!-- Kontak & Pembayaran -->
    <div class="contact-section">
        <div>
            <strong>Kontak Kami</strong><br>
            📞 0822-3072-5758 / (0331) 3058888<br>
            ✉ cs@inds88trans.co.id<br>
            📷 Instagram: @inds88trans
        </div>
        <div>
            <strong>Pembayaran diterima</strong><br>
            {{ $booking->updated_at->translatedFormat('l, d F Y H:i') }}
        </div>
    </div>

    <!-- Footer -->
    <div class="print-footer">
        Dicetak: {{ now()->translatedFormat('Y-m-d H:i:s') }} WIB<br>
        Ind's 88 Trans — Perusahaan Otobus & Tour & Travel<br>
        Tiket ini sah tanpa tanda tangan basah. Berlaku untuk satu kali perjalanan.
    </div>
</div>
</body>
</html>