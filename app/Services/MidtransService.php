<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;   
use App\Models\Booking;   

class MidtransService
{
    protected string $serverKey;
    protected string $baseUrl;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->baseUrl = config('midtrans.base_url');
        $this->isProduction = config('midtrans.is_production');
    }

    /**
     * Create Snap Token for payment
     */
    public function createSnapToken(array $params): ?string
    {
        $snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($snapUrl, $params);

            if ($response->successful()) {
                return $response->json('token');
            }

            Log::error('Midtrans Snap Token Error', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build transaction parameters for Snap
     */
    public function buildTransactionParams(
        string $orderId,
        int $grossAmount,
        string $firstName,
        string $email,
        string $phone,
        array $itemDetails = []
    ): array {
        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $firstName,
                'email' => $email,
                'phone' => $phone,
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => url('/payment/finish'),
            ],
        ];
    }

    /**
     * CREATE TRANSACTION (UNTUK MOBILE & API)
     * 🔥 FIX: cek dulu apakah sudah ada payment pending, kalau ada pakai yang lama
     */
    public function createTransaction(Booking $booking)
    {
        // 🔥 CEK EXISTING PAYMENT - hindari duplicate
        $existing = Payment::where('payable_id', $booking->id)
            ->where('payable_type', Booking::class)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existing && $existing->snap_token) {
            Log::info('Pakai snap token lama untuk booking: ' . $booking->id);
            return $existing;
        }

        // Generate order ID baru
        $orderId = 'BOOK-' . $booking->id . '-' . time();

        // Ambil data user
        $firstName = $booking->user->name ?? 'User';
        $email = $booking->user->email ?? 'test@mail.com';
        $phone = $booking->user->phone ?? '08123456789';

        // Item detail
        $items = [
            [
                'id' => $booking->id,
                'price' => (int) $booking->total_price,
                'quantity' => 1,
                'name' => 'Booking Bus #' . $booking->booking_code,
            ]
        ];

        // Build params
        $params = $this->buildTransactionParams(
            $orderId,
            (int) $booking->total_price,
            $firstName,
            $email,
            $phone,
            $items
        );

        // Ambil snap token
        $snapToken = $this->createSnapToken($params);

        if (!$snapToken) {
            throw new \Exception('Gagal mendapatkan Snap Token dari Midtrans');
        }

        // Simpan ke database payments
        return Payment::create([
            'payable_id' => $booking->id,
            'payable_type' => Booking::class,
            'midtrans_order_id' => $orderId,
            'snap_token' => $snapToken,
            'amount' => $booking->total_price,
            'status' => 'pending',
        ]);
    }

    /**
     * Verify notification signature
     */
    public function verifySignature(array $notification): bool
    {
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];

        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return $signature === ($notification['signature_key'] ?? '');
    }

    /**
     * Get transaction status from Midtrans
     */
    public function getTransactionStatus(string $orderId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->get("{$this->baseUrl}/{$orderId}/status");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Midtrans Status Check Failed', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Status Check Error: ' . $e->getMessage());
            return null;
        }
    }
}