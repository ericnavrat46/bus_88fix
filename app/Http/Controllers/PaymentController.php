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
        $bookingId = $request->booking_id;

        if (!$bookingId) {
            return response()->json(['status' => false, 'message' => 'booking_id wajib diisi'], 422);
        }

        // Cari di semua tabel
        $booking = Booking::find($bookingId)
            ?? Rental::find($bookingId)
            ?? \App\Models\TourBooking::find($bookingId);

        if (!$booking) {
            return response()->json(['status' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

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
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
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

        // Broadcast perubahan status
        broadcast(new \App\Events\PaymentStatusUpdated($payment, $status));

        $this->updatePayableStatus($payment, $status);

        return response()->json(['message' => 'OK']);
    }

    /**
     * CHECK STATUS DARI MIDTRANS LANGSUNG (UNTUK MOBILE TANPA WEBHOOK)
     * 🔥 FIX: pakai DB::table() langsung biar pasti update
     */
    public function checkStatus(Request $request, $bookingId)
{
    $payment = Payment::where('payable_id', $bookingId)
        ->whereIn('payable_type', [
            \App\Models\Booking::class,
            \App\Models\Rental::class,
            \App\Models\TourBooking::class,
        ])
        ->latest()
        ->first();

    if (!$payment) {
        return response()->json([
            'status' => false,
            'message' => 'Payment tidak ditemukan',
            'payment_status' => null,
        ]);
    }

    if ($payment->status === 'paid') {
        return response()->json([
            'status' => true,
            'payment_status' => 'paid',
        ]);
    }

    $orderId = $payment->midtrans_order_id;
    $statusData = $this->midtrans->getTransactionStatus($orderId);

    if (!$statusData) {
        return response()->json([
            'status' => false,
            'message' => 'Gagal cek ke Midtrans',
            'payment_status' => null,
        ]);
    }

    $transactionStatus = $statusData['transaction_status'] ?? 'pending';
    $fraudStatus = $statusData['fraud_status'] ?? null;
    $status = $this->mapTransactionStatus($transactionStatus, $fraudStatus);

    $payment->update([
        'status' => $status,
        'midtrans_transaction_id' => $statusData['transaction_id'] ?? $payment->midtrans_transaction_id,
        'payment_type' => $statusData['payment_type'] ?? $payment->payment_type,
        'raw_response' => $statusData,
    ]);

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
        return ($fraudStatus === 'deny') ? 'canceled' : 'paid';
    }
    return match ($transactionStatus) {
        'settlement', 'success' => 'paid',
        'pending' => 'pending',
        'deny', 'cancel', 'expire' => 'canceled',
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
                'paid' => 'paid',
                'pending' => 'pending',
                'canceled' => 'canceled',
                'refund' => 'refund',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $bookingStatus,
                'paid_at' => $isSuccess ? now() : $payable->paid_at,
                'payment_method' => $payment->payment_type ?? $payable->payment_method,
            ]);

            // Notification for Ticket Booking
            if ($bookingStatus === 'paid') {
                \App\Models\Notification::send($payable->user_id, 'Pembayaran Tiket Berhasil!', "Tiket bus {$payable->booking_code} Anda telah lunas. Selamat menikmati perjalanan!", 'booking', ['booking_id' => $payable->id]);
            } elseif (in_array($bookingStatus, ['canceled'])) {
                \App\Models\Notification::send($payable->user_id, 'Status Tiket Bus', "Pemesanan tiket {$payable->booking_code} Anda telah dibatalkan.", 'booking', ['booking_id' => $payable->id]);
            }
        }

        if ($payable instanceof Rental) {
            $rentalStatus = match ($status) {
                'paid' => 'paid',
                'pending' => 'pending',
                'canceled' => 'canceled',
                'refund' => 'refund',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $rentalStatus,
                'paid_at' => $isSuccess ? now() : $payable->paid_at,
                'payment_method' => $payment->payment_type ?? $payable->payment_method,
            ]);

            // Notification for Rental
            if ($rentalStatus === 'paid') {
                \App\Models\Notification::send($payable->user_id, 'Pembayaran Sewa Berhasil!', "Pembayaran sewa bus {$payable->rental_code} telah lunas. Armada kami siap untuk Anda.", 'rental', ['rental_id' => $payable->id]);
            } elseif (in_array($rentalStatus, ['canceled'])) {
                \App\Models\Notification::send($payable->user_id, 'Status Sewa Bus', "Pemesanan sewa bus {$payable->rental_code} Anda telah dibatalkan.", 'rental', ['rental_id' => $payable->id]);
            }
        }

        if ($payable instanceof \App\Models\TourBooking) {
            $tourStatus = match ($status) {
                'paid' => 'paid',
                'pending' => 'pending',
                'canceled' => 'canceled',
                'refund' => 'refund',
                default => $payable->payment_status,
            };

            $payable->update([
                'payment_status' => $tourStatus,
                'paid_at' => $isSuccess ? now() : $payable->paid_at,
                'payment_method' => $payment->payment_type ?? $payable->payment_method,
            ]);

            // Notification for Tour
            if ($tourStatus === 'paid') {
                \App\Models\Notification::send($payable->user_id, 'Pembayaran Paket Wisata Berhasil!', "Pembayaran paket wisata {$payable->booking_code} telah lunas. Sampai jumpa di destinasi tujuan!", 'tour', ['booking_id' => $payable->id]);
            } elseif (in_array($tourStatus, ['canceled'])) {
                \App\Models\Notification::send($payable->user_id, 'Status Paket Wisata', "Pemesanan tour {$payable->booking_code} Anda telah dibatalkan.", 'tour', ['booking_id' => $payable->id]);
            }
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

        // Jika tidak ketemu di tabel payment, coba cari dari tabel utama dan ambil/buat record payment-nya
        if (!$payment && $orderId) {
            $payable = Rental::where('rental_code', $orderId)->first() 
                      ?? Booking::where('booking_code', $orderId)->first() 
                      ?? \App\Models\TourBooking::where('booking_code', $orderId)->first();
            
            if ($payable) {
                $payment = $payable->payments()->latest()->first();
            }
        }
        
        if ($payment) {
            $statusData = $this->midtrans->getTransactionStatus($orderId);
            
            $isValidResponse = $statusData && isset($statusData['status_code']) && in_array($statusData['status_code'], ['200', '201', '202']);
            
            if ($isValidResponse) {
                $rawStatus = $statusData['transaction_status'] ?? $status;
                $fraudStatus = $statusData['fraud_status'] ?? null;
                $status = $this->mapTransactionStatus($rawStatus, $fraudStatus);
                
                $payment->update([
                    'status' => $status,
                    'midtrans_transaction_id' => $statusData['transaction_id'] ?? $payment->midtrans_transaction_id,
                    'payment_type' => $statusData['payment_type'] ?? $payment->payment_type,
                    'raw_response' => $statusData
                ]);
            } else {
                $status = $this->mapTransactionStatus($status, null);
                $payment->update([
                    'status' => $status,
                    'payment_type' => $request->get('payment_type') ?? $payment->payment_type,
                ]);
            }
            
            $this->updatePayableStatus($payment, $status);
            broadcast(new \App\Events\PaymentStatusUpdated($payment, $status));

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
                'status' => $status,
                'orderId' => $orderId,
                'redirectUrl' => route($redirectRoute, $id),
                'payment' => $payment
            ]);
        }

        // --- FALLBACK: Jika benar-benar buntu ---
        return view('payment.finish', [
            'orderId' => $orderId, 
            'status' => $status,
            'redirectUrl' => route('dashboard'),
            'payment' => null
        ]);
    }
}