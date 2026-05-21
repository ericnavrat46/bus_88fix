<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Refund;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RefundController extends Controller
{
    /**
     * Show refund request form
     */
    public function create(Booking $booking)
    {
        // Check ownership
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if already refunded or requested
        if ($booking->payment_status === 'refunded' || $booking->payment_status === 'pending_refund') {
            return redirect()->back()->with('error', 'Refund sudah diajukan atau sudah diproses.');
        }

        // Check policy
        $departure = Carbon::parse($booking->schedule->departure_date->format('Y-m-d') . ' ' . $booking->schedule->departure_time);
        $hoursDiff = now()->diffInHours($departure, false);

        if ($hoursDiff < 24) {
            return redirect()->back()->with('error', 'Refund hanya dapat dilakukan maksimal H-1 (24 jam) sebelum keberangkatan.');
        }

        if ($hoursDiff >= 24) {
            $refundPercentage = 90;
        } else {
            $refundPercentage = 70;
        }

        $refundAmount = ($booking->total_price * $refundPercentage) / 100;

        return view('dashboard.refund-request', compact('booking', 'refundAmount', 'refundPercentage'));
    }

    /**
     * Store refund request
     */
    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        $request->validate([
            'reason' => 'required|string|min:10',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        if ($booking->payment_status === 'pending_refund') {
            return redirect()->back()->with('error', 'Refund sedang dalam proses verifikasi.');
        }

        $departure = Carbon::parse($booking->schedule->departure_date->format('Y-m-d') . ' ' . $booking->schedule->departure_time);
        $hoursDiff = now()->diffInHours($departure, false);

        if ($hoursDiff < 24) {
            return redirect()->back()->with('error', 'Batas waktu pengajuan refund (minimal 24 jam/H-1 sebelum berangkat) telah habis.');
        }

        if ($hoursDiff >= 24) {
            $refundPercentage = 90;
        } else {
            $refundPercentage = 70;
        }

        $refundAmount = ($booking->total_price * $refundPercentage) / 100;

        Refund::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'refund_amount' => $refundAmount,
            'reason' => $request->reason,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'status' => 'pending',
        ]);

        // Update booking status to pending_refund so it cannot be used/printed
        $booking->update(['payment_status' => 'pending_refund']);

        return redirect()->route('dashboard.booking', $booking)->with('success', 'Permintaan refund berhasil dikirim. Tiket Anda kini dalam status "Menunggu Verifikasi Refund".');
    }

    /**
     * Admin: List refund requests
     */
    public function adminIndex()
    {
        $refunds = Refund::with(['booking', 'user'])->latest()->paginate(10);
        return view('admin.transactions.refunds', compact('refunds'));
    }

    /**
     * Admin: Edit refund request (separate page)
     */
    public function adminEdit(Refund $refund)
    {
        return view('admin.transactions.refund-edit', compact('refund'));
    }

    /**
     * Admin: Process refund
     */
    public function adminAction(Request $request, Refund $refund)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'admin_notes' => 'nullable|string'
        ]);

        $refund->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
        ]);

        if ($request->status === 'completed') {
            $refund->booking->update(['payment_status' => 'refunded']);
        }
        if ($request->status === 'rejected') {
            $refund->booking->update(['payment_status' => 'paid']);
        }
        [$title, $body] = match ($request->status) {
            'approved'  => [
                '✅ Refund Disetujui',
                'Refund booking ' . $refund->booking->booking_code . ' telah disetujui. Dana akan segera ditransfer.',
            ],
            'rejected'  => [
                '❌ Refund Ditolak',
                'Maaf, refund booking ' . $refund->booking->booking_code . ' ditolak. Hubungi admin untuk info lebih lanjut.',
            ],
            'completed' => [
                '💸 Refund Selesai',
                'Dana refund booking ' . $refund->booking->booking_code . ' telah berhasil ditransfer ke rekening kamu.',
            ],
            default => [
                '🔔 Update Refund',
                'Status refund booking ' . $refund->booking->booking_code . ' telah diperbarui.',
            ],
        };

        \App\Helpers\NotificationHelper::send(
            $refund->user_id,
            $title,
            $body,
            'refund_status',
            ['refund_id' => (string) $refund->id, 'status' => $request->status]
        );

        $user = User::find($refund->user_id);
        if ($user && $user->fcm_token) {
            $this->sendFcmSingle(
                $user->fcm_token,
                $title,
                $body,
                ['type' => 'refund_status', 'refund_id' => (string) $refund->id]
            );
        }
        return redirect()->back()->with('success', 'Status refund berhasil diupdate.');
    }
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
        $path = storage_path('app/firebase-service-account.json');
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