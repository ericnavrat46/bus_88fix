<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 

class PaymentController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * CREATE MIDTRANS TRANSACTION (UNTUK MOBILE)
     */
    public function create(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        try {
            $payment = $this->midtrans->createTransaction($booking);

            return response()->json([
                'status' => true,
                'message' => 'Snap token berhasil dibuat',
                'snap_token' => $payment->snap_token,
                'order_id' => $payment->midtrans_order_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Create Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat transaksi',
            ], 500);
        }
    }

    /**
     * Midtrans Notification Handler (Webhook)
     */
    public function notification(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Notification Received', $payload);

        if (!$this->midtrans->verifySignature($payload)) {
            Log::warning('Midtrans Invalid Signature', $payload);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $paymentType = $payload['payment_type'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        if (!$payment) {
            Log::warning('Payment not found for order: ' . $orderId);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $status = $this->mapTransactionStatus($transactionStatus, $fraudStatus);

        $payment->update([
            'status' => $status,
            'midtrans_transaction_id' => $transactionId,
            'payment_type' => $paymentType,
            'raw_response' => $payload,
        ]);

        $this->updatePayableStatus($payment, $status);

        return response()->json(['message' => 'OK']);
    }

    /**
     * CHECK STATUS DARI MIDTRANS LANGSUNG (UNTUK MOBILE TANPA WEBHOOK)
     * 🔥 FIX: pakai DB::table() langsung biar pasti update
     */
    public function checkStatus(Request $request, $orderId)
    {
        $statusData = $this->midtrans->getTransactionStatus($orderId);

        if (!$statusData) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil status dari Midtrans',
            ], 500);
        }

        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        if (!$payment) {
            return response()->json(['status' => false, 'message' => 'Payment tidak ditemukan'], 404);
        }

        $transactionStatus = $statusData['transaction_status'] ?? 'pending';
        $fraudStatus = $statusData['fraud_status'] ?? null;
        $status = $this->mapTransactionStatus($transactionStatus, $fraudStatus);

        // Update payment table
        $payment->update([
            'status' => $status,
            'midtrans_transaction_id' => $statusData['transaction_id'] ?? $payment->midtrans_transaction_id,
            'payment_type' => $statusData['payment_type'] ?? $payment->payment_type,
            'raw_response' => $statusData,
        ]);

        // Update related payable
        $this->updatePayableStatus($payment, $status);

        return response()->json([
            'status' => true,
            'payment_status' => $status,
            'order_id' => $orderId,
        ]);
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

        if ($payable instanceof \App\Models\TourBooking) {
            $tourStatus = match ($status) {
                'settlement', 'capture' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel', 'deny' => 'cancelled',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $tourStatus,
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

        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        
        if ($payment) {
            // Re-sync with Midtrans to be sure
            $statusData = $this->midtrans->getTransactionStatus($orderId);
            if ($statusData) {
                $status = $this->mapTransactionStatus($statusData['transaction_status'], $statusData['fraud_status'] ?? null);
                $payment->update(['status' => $status]);
                $this->updatePayableStatus($payment, $status);
            }

            $redirectRoute = 'dashboard';
            if ($payment->payable_type === Rental::class) {
                $redirectRoute = 'dashboard.rental';
                $id = $payment->payable_id;
            } elseif ($payment->payable_type === Booking::class) {
                $redirectRoute = 'dashboard.booking';
                $id = $payment->payable_id;
            } elseif ($payment->payable_type === \App\Models\TourBooking::class) {
                $redirectRoute = 'dashboard.tour';
                $id = $payment->payable_id;
            }

            if (isset($id)) {
                return view('payment.finish', [
                    'orderId' => $orderId,
                    'status' => $status,
                    'redirectUrl' => route($redirectRoute, $id)
                ]);
            }
        }

        return view('payment.finish', compact('orderId', 'status'));
    }
}