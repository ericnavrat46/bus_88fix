<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Refund;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RefundController extends Controller
{
    public function checkRefund(Request $request, $bookingId)
    {
        $booking = Booking::with('schedule')->find($bookingId);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        if ($booking->user_id != $request->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($booking->payment_status == 'refund' || $booking->payment_status == 'pending_refund') {
            return response()->json(['success' => false, 'message' => 'Refund sudah diajukan']);
        }

        $departureDate = Carbon::parse($booking->schedule->departure_date)->format('Y-m-d');
        $departure     = Carbon::parse($departureDate . ' ' . $booking->schedule->departure_time);
        $hoursDiff     = now()->diffInHours($departure, false);

        if ($hoursDiff < 6) {
            return response()->json(['success' => false, 'message' => 'Refund tidak tersedia kurang dari 6 jam sebelum keberangkatan']);
        }

        $refundPercentage = $hoursDiff >= 24 ? 90 : 70;
        $refundAmount     = ($booking->total_price * $refundPercentage) / 100;

        return response()->json([
            'success'           => true,
            'refund_percentage' => $refundPercentage,
            'refund_amount'     => $refundAmount,
            'hours_left'        => $hoursDiff,
            'booking_id'        => $booking->id,
        ]);
    }

    public function submitRefund(Request $request, $bookingId)
    {
        $booking = Booking::with('schedule')->find($bookingId);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        if ($booking->user_id != $request->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'reason'         => 'required|string|min:10',
            'bank_name'      => 'required|string',
            'account_number' => 'required|string',
            'account_name'   => 'required|string',
        ]);

        $departureDate = Carbon::parse($booking->schedule->departure_date)->format('Y-m-d');
        $departure     = Carbon::parse($departureDate . ' ' . $booking->schedule->departure_time);
        $hoursDiff     = now()->diffInHours($departure, false);

        if ($hoursDiff < 6) {
            return response()->json(['success' => false, 'message' => 'Refund tidak tersedia']);
        }

        $refundPercentage = $hoursDiff >= 24 ? 90 : 70;
        $refundAmount     = ($booking->total_price * $refundPercentage) / 100;

        // Simpan refund — tanpa notif ke admin
        Refund::create([
            'booking_id'     => $booking->id,
            'user_id'        => $request->user_id,
            'refund_amount'  => $refundAmount,
            'reason'         => $request->reason,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'status'         => 'pending',
        ]);

        $booking->update(['payment_status' => 'pending_refund']);

        return response()->json(['success' => true, 'message' => 'Refund berhasil diajukan']);
    }

    // ── DIPANGGIL ADMIN SAAT UPDATE STATUS → NOTIF KE USER ──────
    public function updateStatus(Request $request, $refundId)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,processed',
        ]);

        $refund = Refund::with('booking')->findOrFail($refundId);
        $refund->update(['status' => $request->status]);

        // Update payment_status booking
        if (in_array($request->status, ['approved', 'processed'])) {
            $refund->booking->update(['payment_status' => 'refund']);
        } elseif ($request->status === 'rejected') {
            $refund->booking->update(['payment_status' => 'paid']);
        }

        // ── NOTIF KE USER SAJA ───────────────────────────────────
        [$title, $body] = match ($request->status) {
            'approved'  => [
                '✅ Refund Disetujui',
                'Refund booking ' . $refund->booking->booking_code . ' disetujui. Dana segera ditransfer.',
            ],
            'rejected'  => [
                '❌ Refund Ditolak',
                'Refund booking ' . $refund->booking->booking_code . ' ditolak. Hubungi admin untuk info lebih lanjut.',
            ],
            'processed' => [
                '💸 Refund Diproses',
                'Dana refund booking ' . $refund->booking->booking_code . ' sedang diproses ke rekening kamu.',
            ],
            default => ['🔔 Status Refund', 'Status refund kamu telah diperbarui.'],
        };

        // Simpan ke tabel notifications (in-app)
        \App\Helpers\NotificationHelper::send(
            $refund->user_id,
            $title,
            $body,
            'refund_status',
            ['refund_id' => (string) $refund->id, 'status' => $request->status]
        );

        // Push FCM ke HP user
        $user = User::find($refund->user_id);
        if ($user && $user->fcm_token) {
            $this->sendFcmSingle(
                $user->fcm_token,
                $title,
                $body,
                ['type' => 'refund_status', 'refund_id' => (string) $refund->id]
            );
        }
        // ─────────────────────────────────────────────────────────

        return response()->json(['success' => true, 'message' => 'Status refund diperbarui']);
    }

    // ══════════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════════════════════════════

    private function sendFcmSingle(string $token, string $title, string $body, array $data = []): void
    {
        $projectId   = config('services.firebase.project_id');
        $accessToken = $this->getFirebaseAccessToken();

        Http::withToken($accessToken)
            ->post(
                "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                [
                    'message' => [
                        'token'        => $token,
                        'notification' => ['title' => $title, 'body' => $body],
                        'data'         => $data,
                        'android'      => [
                            'notification' => [
                                'channel_id' => 'high_importance_channel',
                                'sound'      => 'default',
                            ],
                        ],
                    ],
                ]
            );
    }

    private function getFirebaseAccessToken(): string
    {
        $path           = storage_path('app/firebase/service-account.json');
        $serviceAccount = json_decode(file_get_contents($path), true);

        $now     = time();
        $header  = rtrim(strtr(base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])), '+/', '-_'), '=');
        $payload = rtrim(strtr(base64_encode(json_encode([
            'iss'   => $serviceAccount['client_email'],
            'sub'   => $serviceAccount['client_email'],
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ])), '+/', '-_'), '=');

        openssl_sign("{$header}.{$payload}", $sig, $serviceAccount['private_key'], 'SHA256');
        $jwt = "{$header}.{$payload}." . rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        return $response->json('access_token');
    }
}