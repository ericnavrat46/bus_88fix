<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Midtrans Notification Handler (Webhook)
     */
    public function notification(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Notification Received', $payload);

        // Verify signature
        if (!$this->midtrans->verifySignature($payload)) {
            Log::warning('Midtrans Invalid Signature', $payload);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $paymentType = $payload['payment_type'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        // Find the payment record
        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        if (!$payment) {
            Log::warning('Payment not found for order: ' . $orderId);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Determine payment status
        $status = $this->mapTransactionStatus($transactionStatus, $fraudStatus);

        // Update payment
        $payment->update([
            'status' => $status,
            'midtrans_transaction_id' => $transactionId,
            'payment_type' => $paymentType,
            'raw_response' => $payload,
        ]);

        // Update related booking or rental
        $this->updatePayableStatus($payment, $status);

        return response()->json(['message' => 'OK']);
    }

    /**
     * Map Midtrans transaction status
     */
    protected function mapTransactionStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        if ($transactionStatus === 'capture') {
            return ($fraudStatus === 'accept') ? 'capture' : 'deny';
        }

        return match ($transactionStatus) {
            'settlement' => 'settlement',
            'pending' => 'pending',
            'deny' => 'deny',
            'cancel' => 'cancel',
            'expire' => 'expire',
            'refund', 'partial_refund' => 'refund',
            default => $transactionStatus,
        };
    }

    /**
     * Update the related booking or rental status
     */
    protected function updatePayableStatus(Payment $payment, string $status): void
    {
        $payable = $payment->payable;
        if (!$payable) return;

        if ($payable instanceof Booking) {
            $bookingStatus = match ($status) {
                'settlement', 'capture' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel', 'deny' => 'cancelled',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $bookingStatus,
                'paid_at' => in_array($status, ['settlement', 'capture']) ? now() : $payable->paid_at,
            ]);
        }

        if ($payable instanceof Rental) {
            $rentalStatus = match ($status) {
                'settlement', 'capture' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel', 'deny' => 'cancelled',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $rentalStatus,
                'paid_at' => in_array($status, ['settlement', 'capture']) ? now() : $payable->paid_at,
            ]);
        }
    }

    /**
     * Payment finish callback page
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        $status = $request->get('transaction_status', 'pending');

        return view('payment.finish', compact('orderId', 'status'));
    }
}
