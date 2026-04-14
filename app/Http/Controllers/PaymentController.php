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
        // For credit card / snap capture
        if ($transactionStatus === 'capture') {
            return ($fraudStatus === 'accept' || $fraudStatus === '' || $fraudStatus === null) ? 'settlement' : 'deny';
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

        // Success statuses
        $isSuccess = in_array($status, ['settlement', 'capture', 'success']);

        if ($payable instanceof Booking) {
            $bookingStatus = match ($status) {
                'settlement', 'capture', 'success' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel', 'deny' => 'cancelled',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $bookingStatus,
                'paid_at' => $isSuccess ? now() : $payable->paid_at,
            ]);
        }

        if ($payable instanceof Rental) {
            $rentalStatus = match ($status) {
                'settlement', 'capture', 'success' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel', 'deny' => 'cancelled',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $rentalStatus,
                'paid_at' => $isSuccess ? now() : $payable->paid_at,
            ]);
        }

        if ($payable instanceof \App\Models\TourBooking) {
            $tourStatus = match ($status) {
                'settlement', 'capture', 'success' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel', 'deny' => 'cancelled',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $tourStatus,
                'paid_at' => $isSuccess ? now() : $payable->paid_at,
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
            $statusData = $this->midtrans->getTransactionStatus($orderId);
            
            // Validate if we actually got a valid transaction from Midtrans API
            $isValidResponse = $statusData && isset($statusData['status_code']) && in_array($statusData['status_code'], ['200', '201', '202']);
            
            if ($isValidResponse) {
                $rawStatus = $statusData['transaction_status'] ?? $status;
                $fraudStatus = $statusData['fraud_status'] ?? null;
                $status = $this->mapTransactionStatus($rawStatus, $fraudStatus);
                
                $payment->update([
                    'status' => $status,
                    'raw_response' => $statusData
                ]);
            } else {
                // If API returns 404 (often happens in sandbox testing via URL manipulation),
                // we'll tentatively trust the URL parameter or existing status
                $status = $this->mapTransactionStatus($status, null);
                
                $payment->update([
                    'status' => $status,
                    'raw_response' => $statusData ?? $payment->raw_response
                ]);
            }
            
            $this->updatePayableStatus($payment, $status);

            $redirectRoute = 'dashboard';
            $id = $payment->payable_id;

            if ($payment->payable_type === Rental::class) {
                $redirectRoute = 'dashboard.rental';
            } elseif ($payment->payable_type === Booking::class) {
                $redirectRoute = 'dashboard.booking';
            } elseif ($payment->payable_type === \App\Models\TourBooking::class) {
                $redirectRoute = 'dashboard.tour';
            }

            return view('payment.finish', [
                'orderId' => $orderId,
                'status' => $status,
                'redirectUrl' => route($redirectRoute, $id)
            ]);
        }

        // --- FALLBACK: If payment record not found yet, try finding in main tables ---
        $redirectUrl = route('dashboard');
        
        // Check Rental
        $match = Rental::where('rental_code', $orderId)->first();
        if ($match) {
            $redirectUrl = route('dashboard.rental', $match->id);
        } else {
            // Check Booking
            $match = Booking::where('booking_code', $orderId)->first();
            if ($match) {
                $redirectUrl = route('dashboard.booking', $match->id);
            } else {
                // Check Tour
                $match = \App\Models\TourBooking::where('booking_code', $orderId)->first();
                if ($match) {
                    $redirectUrl = route('dashboard.tour', $match->id);
                }
            }
        }

        // If it's settlement/capture in query but record not yet synced, still show success UI if possible
        // but it's safer to just show whatever status we have.
        
        return view('payment.finish', [
            'orderId' => $orderId, 
            'status' => $status,
            'redirectUrl' => $redirectUrl
        ]);
    }
}