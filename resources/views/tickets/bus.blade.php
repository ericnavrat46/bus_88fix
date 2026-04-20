<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>E-Tiket Bus 88 - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #fff;
        }

        .page {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            background: #fff;
        }

        /* ── HEADER ── */
        .header-strip {
            background: #CC0000;
            height: 8px;
            width: 100%;
        }

        .header-body {
            padding: 14px 24px 12px;
            border-bottom: 2px solid #CC0000;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 200px;
            vertical-align: middle;
        }

        .logo-box {
            display: inline-block;
        }

        .logo-name {
            font-size: 26px;
            font-weight: 900;
            color: #1a3a6b;
            letter-spacing: -1px;
        }

        .logo-name span {
            color: #CC0000;
        }

        .logo-sub {
            font-size: 9px;
            color: #555;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .address-cell {
            text-align: right;
            vertical-align: top;
        }

        .address-title {
            font-size: 11px;
            font-weight: 700;
            color: #CC0000;
            margin-bottom: 3px;
        }

        .address-text {
            font-size: 9.5px;
            color: #555;
            line-height: 1.5;
        }

        /* ── INFO BAR ── */
        .info-bar {
            background: #f5f5f5;
            border-bottom: 1px solid #ddd;
        }

        .info-bar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-alert {
            background: #fffbeb;
            border-right: 1px solid #ddd;
            padding: 8px 14px;
            width: 200px;
            vertical-align: middle;
        }

        .info-alert-label {
            font-size: 8px;
            font-weight: 700;
            color: #92400e;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-alert-text {
            font-size: 9px;
            font-weight: 700;
            color: #92400e;
        }

        .info-contacts {
            padding: 8px 14px;
            vertical-align: middle;
        }

        .info-contacts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .contact-col {
            padding-right: 20px;
            vertical-align: top;
        }

        .contact-label {
            font-size: 8.5px;
            color: #777;
            margin-bottom: 2px;
        }

        .contact-value {
            font-size: 10px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .contact-value a {
            color: #CC0000;
            text-decoration: none;
        }

        /* ── ROUTE SECTION ── */
        .route-section {
            padding: 18px 24px 12px;
            border-bottom: 1px solid #e8e8e8;
        }

        .route-table {
            width: 100%;
            border-collapse: collapse;
        }

        .route-origin {
            width: 38%;
            vertical-align: top;
        }

        .route-arrow {
            width: 8%;
            text-align: center;
            vertical-align: middle;
            padding-top: 4px;
        }

        .route-destination {
            width: 38%;
            vertical-align: top;
        }

        .route-booking {
            width: 16%;
            vertical-align: top;
            text-align: right;
        }

        .route-city {
            font-size: 17px;
            font-weight: 900;
            color: #CC0000;
            margin-bottom: 3px;
        }

        .route-station {
            font-size: 10px;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .route-datetime {
            font-size: 10px;
            color: #555;
            line-height: 1.5;
        }

        .route-arrow-img {
            font-size: 16px;
            color: #1a3a6b;
            font-weight: 900;
        }

        .booking-code-box {
            border: 1px solid #1a3a6b;
            border-radius: 4px;
            padding: 6px 8px;
            text-align: center;
            display: inline-block;
        }

        .booking-code-label {
            font-size: 8px;
            color: #777;
            margin-bottom: 2px;
        }

        .booking-code-value {
            font-size: 9px;
            font-weight: 700;
            color: #CC0000;
            word-break: break-all;
        }

        /* ── TOTAL HARGA ── */
        .total-bar {
            background: #f9f9f9;
            border: 1px solid #e8e8e8;
            margin: 0 24px 0;
            padding: 10px 16px;
            border-radius: 0;
        }

        .total-harga {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .total-time {
            font-size: 9px;
            color: #777;
            margin-top: 2px;
        }

        /* ── SECTIONS ── */
        .section-wrapper {
            border: 1px solid #e8e8e8;
            margin: 10px 24px 0;
        }

        .section-table {
            width: 100%;
            border-collapse: collapse;
        }

        .section-left {
            width: 170px;
            background: #fafafa;
            border-right: 1px solid #e8e8e8;
            padding: 16px;
            vertical-align: top;
        }

        .section-icon {
            font-size: 20px;
            margin-bottom: 6px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            color: #333;
        }

        .section-right {
            padding: 16px;
            vertical-align: top;
        }

        /* Journey detail rows */
        .journey-table {
            width: 100%;
            border-collapse: collapse;
        }

        .journey-bus {
            font-size: 13px;
            font-weight: 800;
            color: #1a1a1a;
            padding-bottom: 12px;
        }

        .journey-point-row td {
            padding: 0;
            vertical-align: top;
        }

        .journey-dot-cell {
            width: 20px;
            text-align: center;
            padding-top: 2px;
        }

        .journey-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #1a3a6b;
            display: inline-block;
        }

        .journey-line-cell {
            width: 20px;
            text-align: center;
        }

        .journey-line {
            width: 1px;
            height: 30px;
            background: #ccc;
            margin: 0 auto;
        }

        .journey-content {
            padding-left: 8px;
            padding-bottom: 10px;
        }

        .journey-dir-label {
            font-size: 9px;
            color: #777;
            margin-bottom: 2px;
        }

        .journey-place {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1px;
        }

        .journey-address {
            font-size: 9px;
            color: #777;
            line-height: 1.4;
        }

        /* Passenger section */
        .pass-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pass-info-cell {
            vertical-align: top;
            width: 60%;
        }

        .pass-qr-cell {
            vertical-align: top;
            text-align: right;
            width: 40%;
        }

        .pass-row {
            margin-bottom: 6px;
        }

        .pass-label {
            font-size: 9px;
            color: #888;
            margin-bottom: 1px;
        }

        .pass-value {
            font-size: 11px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .pass-code {
            font-size: 9px;
            color: #888;
            word-break: break-all;
        }

        .qr-img {
            width: 100px;
            height: 100px;
        }

        .qr-price {
            font-size: 12px;
            font-weight: 700;
            color: #CC0000;
            text-align: right;
            margin-top: 4px;
        }

        .qr-timestamp {
            font-size: 8px;
            color: #999;
            text-align: right;
        }

        /* Contact section */
        .contact-section {
            border: 1px solid #e8e8e8;
            margin: 10px 24px 0;
        }

        .contact-section-table {
            width: 100%;
            border-collapse: collapse;
        }

        .contact-section-left {
            width: 170px;
            background: #fafafa;
            border-right: 1px solid #e8e8e8;
            padding: 14px 16px;
            vertical-align: middle;
        }

        .contact-section-right {
            padding: 14px 16px;
            vertical-align: middle;
        }

        .contact-detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .contact-detail-col {
            padding-right: 24px;
            vertical-align: top;
        }

        /* Footer */
        .print-footer {
            margin: 14px 24px 20px;
            padding-top: 10px;
            border-top: 1px solid #e8e8e8;
            font-size: 8.5px;
            color: #999;
            text-align: center;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="page">

        {{-- ── Header Strip ── --}}
        <div class="header-strip"></div>

        {{-- ── Header Body ── --}}
        <div class="header-body">
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <table style="border-collapse:collapse;">
                            <tr>
                                <td style="vertical-align:middle;padding-right:10px;">
                                    <div
                                        style="width:36px;height:36px;background:#CC0000;border-radius:6px;text-align:center;line-height:36px;">
                                        <span style="color:#fff;font-size:14px;font-weight:900;">88</span>
                                    </div>
                                </td>
                                <td style="vertical-align:middle;">
                                    <div class="logo-name">BUS <span>88</span></div>
                                    <div class="logo-sub">Perusahaan Otobus &amp; Travel</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="address-cell">
                        <div class="address-title">Kantor Pusat Bus 88</div>
                        <div class="address-text">
                            Jl. Brawijaya, Darungan, Jubung, Kec. Sukorambi,<br>
                            Kab. Jember, Jawa Timur 68151
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ── Info Bar ── --}}
        <div class="info-bar">
            <table class="info-bar-table">
                <tr>
                    <td class="info-alert">
                        <div class="info-alert-label">Penting</div>
                        <div class="info-alert-text">Kode QR anda harus dipindai saat naik.</div>
                    </td>
                    <td class="info-contacts">
                        <table class="info-contacts-table">
                            <tr>
                                <td class="contact-col">
                                    <div class="contact-label">Contact Center</div>
                                    <div class="contact-value">(0331) 3058888</div>
                                </td>
                                <td class="contact-col">
                                    <div class="contact-label">Email Customer Service</div>
                                    <div class="contact-value">cs@bus88.co.id</div>
                                </td>
                                <td class="contact-col">
                                    <div class="contact-label">Website</div>
                                    <div class="contact-value"><a href="#">www.bus88.co.id</a></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ── Route Section ── --}}
        <div class="route-section">
            <table class="route-table">
                <tr>
                    <td class="route-origin">
                        <div class="route-city">{{ $booking->schedule->route->origin }}</div>
                        @if($booking->schedule->route->origin_detail ?? false)
                            <div class="route-station">{{ $booking->schedule->route->origin_detail }}</div>
                        @endif
                        <div class="route-datetime">
                            {{ \Carbon\Carbon::parse($booking->schedule->departure_date)->translatedFormat('l, d F Y') }}<br>
                            {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }} WIB
                        </div>
                    </td>
                    <td class="route-arrow">
                        <div class="route-arrow-img"
                            style="font-size:20px;font-weight:900;color:#CC0000;text-align:center;">-&gt;</div>
                    </td>
                    <td class="route-destination">
                        <div class="route-city">{{ $booking->schedule->route->destination }}</div>
                        @if($booking->schedule->route->destination_detail ?? false)
                            <div class="route-station">{{ $booking->schedule->route->destination_detail }}</div>
                        @endif
                        <div class="route-datetime">
                            {{ \Carbon\Carbon::parse($booking->schedule->arrival_date ?? $booking->schedule->departure_date)->translatedFormat('l, d F Y') }}<br>
                            @if($booking->schedule->arrival_time)
                                {{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }} WIB
                            @endif
                        </div>
                    </td>
                    <td class="route-booking">
                        <div class="booking-code-box">
                            <div class="booking-code-label">Kode Booking :</div>
                            <div class="booking-code-value">{{ $booking->booking_code }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ── Total Harga ── --}}
        <div style="padding: 0 24px; margin-top: 10px;">
            <div style="border: 1px solid #e0e0e0; background: #f9f9f9; padding: 10px 16px;">
                <div class="total-harga">Total Harga : Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                <div class="total-time">Pembayaran diterima pada
                    {{ $booking->updated_at->translatedFormat('l, d F Y H:i') }}</div>
            </div>
        </div>

        {{-- ── Rincian Perjalanan ── --}}
        <div class="section-wrapper">
            <table class="section-table">
                <tr>
                    <td class="section-left">
                        <div class="section-icon">&#128652;</div>
                        <div class="section-label">Rincian Perjalanan</div>
                    </td>
                    <td class="section-right">
                        <table class="journey-table">
                            <tr>
                                <td class="journey-bus" colspan="2">
                                    {{ $booking->schedule->bus->name ?? 'Bus 88' }}
                                    @if($booking->schedule->bus->bus_class ?? false)
                                        - {{ $booking->schedule->bus->bus_class }}
                                    @endif
                                </td>
                            </tr>
                            {{-- Berangkat --}}
                            <tr class="journey-point-row">
                                <td class="journey-dot-cell">
                                    <div class="journey-dot"></div>
                                </td>
                                <td class="journey-content">
                                    <div class="journey-dir-label">Berangkat Dari</div>
                                    <div class="journey-place">{{ $booking->schedule->route->origin }}</div>
                                    @if($booking->schedule->route->origin_detail ?? false)
                                        <div class="journey-address">{{ $booking->schedule->route->origin_detail }}</div>
                                    @endif
                                </td>
                            </tr>
                            {{-- Garis --}}
                            <tr>
                                <td class="journey-line-cell">
                                    <div class="journey-line"></div>
                                </td>
                                <td></td>
                            </tr>
                            {{-- Tiba --}}
                            <tr class="journey-point-row">
                                <td class="journey-dot-cell">
                                    <div class="journey-dot" style="background:#CC0000;"></div>
                                </td>
                                <td class="journey-content">
                                    <div class="journey-dir-label">Menuju ke</div>
                                    <div class="journey-place">{{ $booking->schedule->route->destination }}</div>
                                    @if($booking->schedule->route->destination_detail ?? false)
                                        <div class="journey-address">{{ $booking->schedule->route->destination_detail }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ── Rincian Penumpang ── --}}
        @php $firstPassenger = $booking->passengers->first(); @endphp
        <div class="section-wrapper">
            <table class="section-table">
                <tr>
                    <td class="section-left">
                        <div class="section-icon">&#128101;</div>
                        <div class="section-label">Rincian Penumpang</div>
                    </td>
                    <td class="section-right">
                        <table class="pass-table">
                            <tr>
                                <td class="pass-info-cell">
                                    {{-- Loop semua penumpang --}}
                                    @foreach($booking->passengers as $index => $p)
                                        <div
                                            style="margin-bottom: 10px; {{ !$loop->last ? 'border-bottom: 1px dashed #eee; padding-bottom: 8px;' : '' }}">
                                            <div class="pass-row">
                                                <div class="pass-label">Nama Penumpang
                                                    {{ $booking->passengers->count() > 1 ? ($index + 1) : '' }}</div>
                                                <div class="pass-value">{{ $p->passenger_name }}</div>
                                            </div>
                                            <div class="pass-row">
                                                <div class="pass-label">Nomor Kursi</div>
                                                <div class="pass-value">Kursi #{{ $p->seat_number }}</div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                                        <div class="pass-row">
                                            <div class="pass-label">No. Telepon</div>
                                            <div class="pass-value">{{ $booking->user->phone ?? '-' }}</div>
                                        </div>
                                        <div class="pass-row">
                                            <div class="pass-label">Kode Tiket</div>
                                            <div class="pass-code">{{ $booking->booking_code }}</div>
                                        </div>
                                        <div class="pass-row">
                                            <div class="pass-label">Waktu Keberangkatan</div>
                                            <div class="pass-value">
                                                {{ \Carbon\Carbon::parse($booking->schedule->departure_date)->translatedFormat('l, d F Y') }}<br>
                                                Pukul
                                                {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}
                                                WIB
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="pass-qr-cell">
                                    @if(!empty($qrCode))
                                        <img src="data:image/png;base64,{{ $qrCode }}" class="qr-img" alt="QR Code">
                                        <div class="qr-price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </div>
                                        <div class="qr-timestamp">Dicetak: {{ now()->format('Y-m-d H:i:s') }}</div>
                                    @else
                                        <div style="font-size:9px;color:#999;">QR tidak tersedia</div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ── Kontak ── --}}
        <div class="contact-section">
            <table class="contact-section-table">
                <tr>
                    <td class="contact-section-left">
                        <div class="section-label">Kontak</div>
                    </td>
                    <td class="contact-section-right">
                        <table class="contact-detail-table">
                            <tr>
                                <td class="contact-detail-col">
                                    <div class="contact-label">No. Telepon</div>
                                    <div class="contact-value">(0331) 3058888</div>
                                </td>
                                <td class="contact-detail-col">
                                    <div class="contact-label">Email</div>
                                    <div class="contact-value">cs@bus88.co.id</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ── Footer ── --}}
        <div class="print-footer">
            Dicetak: {{ now()->translatedFormat('d F Y, H:i:s') }} WIB &nbsp;|&nbsp;
            Bus 88 — Perusahaan Otobus &amp; Tour &amp; Travel &nbsp;|&nbsp;
            Tiket ini sah tanpa tanda tangan basah. Berlaku untuk satu kali perjalanan.
        </div>

    </div>
</body>

</html>