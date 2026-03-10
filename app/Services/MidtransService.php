<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Status Check Error: ' . $e->getMessage());
            return null;
        }
    }
}
