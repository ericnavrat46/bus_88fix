<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rental;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    // ================================================================
    // BUS TICKET
    // ================================================================

    public function downloadBusTicket(Booking $booking)
    {
        $this->authorizeAccess($booking->user_id);

        if ($booking->payment_status !== 'paid') {
            return back()->with('error', 'Tiket hanya tersedia setelah pembayaran lunas.');
        }

        $booking->load(['schedule.route', 'schedule.bus', 'passengers', 'user']);

        $qrCode = $this->generateQR($this->buildVerifyUrl('bus', $booking->booking_code));

        return Pdf::loadView('tickets.bus', compact('booking', 'qrCode'))
            ->setPaper('a4', 'portrait')
            ->download('E-Ticket-Bus-' . $booking->booking_code . '.pdf');
    }

    public function previewBusTicket(Booking $booking)
    {
        $this->authorizeAccess($booking->user_id);

        if ($booking->payment_status !== 'paid') {
            return back()->with('error', 'Tiket hanya tersedia setelah pembayaran lunas.');
        }

        $booking->load(['schedule.route', 'schedule.bus', 'passengers', 'user']);

        $qrCode = $this->generateQR($this->buildVerifyUrl('bus', $booking->booking_code));

        return Pdf::loadView('tickets.bus', compact('booking', 'qrCode'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }

    // ================================================================
    // RENTAL
    // ================================================================

    public function downloadRentalTicket(Rental $rental)
    {
        $this->authorizeAccess($rental->user_id);

        if ($rental->payment_status !== 'paid') {
            return back()->with('error', 'Tiket hanya tersedia setelah pembayaran lunas.');
        }

        $rental->load(['user', 'bus']);

        $qrCode = $this->generateQR($this->buildVerifyUrl('rental', $rental->rental_code));

        return Pdf::loadView('tickets.rental', compact('rental', 'qrCode'))
            ->setPaper('a4', 'portrait')
            ->download('E-Ticket-Rental-' . $rental->rental_code . '.pdf');
    }

    // ================================================================
    // TOUR
    // ================================================================

    public function downloadTourTicket(TourBooking $booking)
    {
        $this->authorizeAccess($booking->user_id);

        if ($booking->payment_status !== 'paid') {
            return back()->with('error', 'Tiket hanya tersedia setelah pembayaran lunas.');
        }

        $booking->load(['user', 'tourPackage']);

        $qrCode = $this->generateQR($this->buildVerifyUrl('tour', $booking->booking_code));

        return Pdf::loadView('tickets.tour', compact('booking', 'qrCode'))
            ->setPaper('a4', 'portrait')
            ->download('E-Ticket-Tour-' . $booking->booking_code . '.pdf');
    }

    // ================================================================
    // VERIFY PUBLIC
    // ================================================================

    public function verify(Request $request)
    {
        $type  = $request->query('type');
        $code  = $request->query('code');
        $token = $request->query('token');

        if (!$this->verifyToken($code, $token)) {
            return view('tickets.verify', [
                'valid' => false,
                'message' => 'QR Code tidak valid atau telah dimanipulasi.'
            ]);
        }

        $result = $this->dispatchVerify($type, $code);

        Log::info('Ticket QR Verify', compact('type', 'code') + ['valid' => $result['valid']]);

        return view('tickets.verify', $result);
    }

    // ================================================================
    // ADMIN SCAN
    // ================================================================

    public function scanPage()
    {
        return view('tickets.scan');
    }

    public function scanResult(Request $request)
    {
        $request->validate(['qr_data' => 'required|string']);

        $parsed = parse_url(trim($request->qr_data));

        if (empty($parsed['query'])) {
            return response()->json(['valid' => false, 'message' => 'Format QR tidak valid.']);
        }

        parse_str($parsed['query'], $params);

        $type  = $params['type'] ?? null;
        $code  = $params['code'] ?? null;
        $token = $params['token'] ?? null;

        if (!$type || !$code || !$token || !$this->verifyToken($code, $token)) {
            return response()->json(['valid' => false, 'message' => 'QR tidak valid.']);
        }

        $result = $this->dispatchVerify($type, $code);

        return response()->json($result);
    }

    // ================================================================
    // VERIFY LOGIC
    // ================================================================

    private function verifyBus(string $code): array
    {
        $b = Booking::with(['user', 'schedule.route', 'schedule.bus', 'passengers'])
            ->where('booking_code', $code)
            ->first();

        if (!$b) return ['valid' => false, 'message' => 'Kode booking tidak ditemukan.'];
        if ($b->payment_status !== 'paid') return ['valid' => false, 'message' => 'Belum lunas.'];

        $dep = Carbon::parse($b->schedule->departure_date . ' ' . $b->schedule->departure_time);

        $route = $b->schedule->route;
        $ori = $route->origin ?? '-';
        $dst = $route->destination ?? '-';

        return [
            'valid' => true,
            'type' => 'bus',
            'message' => 'Tiket Bus VALID',
            'is_today' => $dep->isToday(),
            'data' => [
                'Kode Booking' => $b->booking_code,
                'Nama' => $b->user->name,
                'Rute' => "$ori → $dst",
                'Tanggal' => $dep->translatedFormat('d F Y'),
                'Jam' => $dep->format('H:i'),
                'Bus' => $b->schedule->bus->name ?? '-',
                'Kursi' => $b->passengers->pluck('seat_number')->implode(', '),
            ]
        ];
    }

    private function verifyRental(string $code): array
    {
        $r = Rental::with(['user', 'bus'])->where('rental_code', $code)->first();

        if (!$r) return ['valid' => false, 'message' => 'Kode tidak ditemukan.'];

        return [
            'valid' => true,
            'type' => 'rental',
            'message' => 'Tiket Rental VALID',
            'data' => [
                'Nama' => $r->user->name,
                'Bus' => $r->bus->name ?? '-',
                'Tujuan' => $r->destination,
            ]
        ];
    }

    private function verifyTour(string $code): array
    {
        $b = TourBooking::with(['user', 'tourPackage'])->where('booking_code', $code)->first();

        if (!$b) return ['valid' => false, 'message' => 'Kode tidak ditemukan.'];

        return [
            'valid' => true,
            'type' => 'tour',
            'message' => 'Tiket Tour VALID',
            'data' => [
                'Nama' => $b->user->name,
                'Paket' => $b->tourPackage->name ?? '-',
            ]
        ];
    }

    private function dispatchVerify($type, $code)
    {
        return match ($type) {
            'bus' => $this->verifyBus($code),
            'rental' => $this->verifyRental($code),
            'tour' => $this->verifyTour($code),
            default => ['valid' => false, 'message' => 'Tipe tidak dikenali']
        };
    }

    // ================================================================
    // UTIL
    // ================================================================

    private function authorizeAccess($userId)
    {
        if ($userId !== Auth::id() && !Auth::user()?->is_admin) {
            abort(403);
        }
    }

    private function buildVerifyUrl($type, $code)
    {
        return route('ticket.verify', [
            'type' => $type,
            'code' => $code,
            'token' => $this->generateToken($code),
        ]);
    }

    private function generateQR($data)
{
    // Encode data untuk URL
    $encodedData = urlencode($data);
    
    // Coba gunakan Google Charts API (lebih stabil)
    $url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl={$encodedData}&choe=UTF-8";
    
    $qrImage = @file_get_contents($url);
    
    if ($qrImage !== false) {
        return base64_encode($qrImage);
    }
    
    // Fallback 1: QuickChart.io (alternatif)
    $fallbackUrl = "https://quickchart.io/qr?text={$encodedData}&size=250";
    $qrImage = @file_get_contents($fallbackUrl);
    
    if ($qrImage !== false) {
        return base64_encode($qrImage);
    }
    
    // Fallback 2: QR Server API
    $secondFallback = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={$encodedData}";
    $qrImage = @file_get_contents($secondFallback);
    
    if ($qrImage !== false) {
        return base64_encode($qrImage);
    }
    
    // Jika semua gagal, return empty string dan catat error
    Log::error('QR Code generation failed for data: ' . $data);
    return '';
}

    private function generateToken($code)
    {
        return hash_hmac('sha256', $code, config('app.key'));
    }

    private function verifyToken($code, $token)
    {
        return hash_equals($this->generateToken($code), $token);
    }
}