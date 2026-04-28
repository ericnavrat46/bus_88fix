<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\ProfileWebController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentProofController;

// Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\FlashSaleController;

// ─────────────────────────────────────────────
// PUBLIC ROUTES
// ─────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'searchSchedules'])->name('schedules.search');
Route::get('/promo/{flashSale}', [HomeController::class, 'promoDetail'])->name('promo.detail');

// Tour
Route::get('/tour', [TourController::class, 'index'])->name('tour.index');
Route::get('/tour/{package:slug}', [TourController::class, 'show'])->name('tour.show');

// Static Pages
Route::get('/about',   [PageController::class, 'about'])->name('about');
Route::get('/terms',   [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Google OAuth
Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ─────────────────────────────────────────────
// AUTH (GUEST)
// ─────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);

    // Reset Password (OTP)
    Route::get('/forgot-password',  [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::get('/verify-otp',       [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
    Route::post('/verify-otp',      [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::get('/reset-password',   [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',  [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ─────────────────────────────────────────────
// PAYMENT (MIDTRANS)
// ─────────────────────────────────────────────
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/finish',        [PaymentController::class, 'finish'])->name('payment.finish');

// ─────────────────────────────────────────────
// TICKET (PUBLIC + USER)
// ─────────────────────────────────────────────

// Public (QR Verify)
Route::get('/ticket/verify', [TicketController::class, 'verify'])->name('ticket.verify');

// User (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/ticket/bus/{booking}/download', [TicketController::class, 'downloadBusTicket'])->name('ticket.bus.download');
    Route::get('/ticket/bus/{booking}/preview',  [TicketController::class, 'previewBusTicket'])->name('ticket.bus.preview');
    Route::get('/ticket/rental/{rental}/download', [TicketController::class, 'downloadRentalTicket'])->name('ticket.rental.download');
    Route::get('/ticket/tour/{booking}/download', [TicketController::class, 'downloadTourTicket'])->name('ticket.tour.download');
});

// ─────────────────────────────────────────────
// USER (AUTH)
// ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/booking/{booking}', [DashboardController::class, 'bookingDetail'])->name('dashboard.booking');
    Route::post('/dashboard/booking/{booking}/cancel', [DashboardController::class, 'cancelBooking'])->name('dashboard.booking.cancel');

    Route::get('/dashboard/rental/{rental}', [DashboardController::class, 'rentalDetail'])->name('dashboard.rental');
    Route::post('/dashboard/rental/{rental}/cancel', [DashboardController::class, 'cancelRental'])->name('dashboard.rental.cancel');

    Route::get('/dashboard/tour/{booking}', [DashboardController::class, 'tourDetail'])->name('dashboard.tour');
    Route::post('/dashboard/tour/{booking}/cancel', [DashboardController::class, 'cancelTour'])->name('dashboard.tour.cancel');

    // Profile
    Route::post('/update-profile', [ProfileWebController::class, 'updateProfile'])->name('profile.update');

    // OTP Password
    Route::post('/send-otp-password', [ProfileWebController::class, 'sendOtpPassword'])->name('password.otp.send');
    Route::post('/verify-otp-only', [ProfileWebController::class, 'verifyOtpOnly'])->name('password.otp.verify.only');
    Route::post('/change-password', [ProfileWebController::class, 'changePassword'])->name('password.change');
    Route::get('/check-verification-status', [ProfileWebController::class, 'checkVerificationStatus'])->name('password.check.status');

    // Booking
    Route::get('/booking/{schedule}/select-seat', [BookingController::class, 'selectSeat'])->name('booking.select-seat');
    Route::post('/booking/{schedule}/passenger-form', [BookingController::class, 'passengerForm'])->name('booking.passenger-form');
    Route::post('/booking/{schedule}/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');

    // Rental
    Route::get('/charter', [RentalController::class, 'index'])->name('rental.index');
    Route::post('/charter', [RentalController::class, 'store'])->name('rental.store');
    Route::get('/charter/{rental}/pay', [RentalController::class, 'pay'])->name('rental.pay');

    // Payment Proof
    Route::post('/booking/{booking}/upload-proof', [PaymentProofController::class, 'uploadBookingProof'])->name('booking.upload-proof');
    Route::post('/rental/{rental}/upload-proof', [PaymentProofController::class, 'uploadRentalProof'])->name('rental.upload-proof');
    Route::post('/tour/{booking}/upload-proof', [PaymentProofController::class, 'uploadTourProof'])->name('tour.upload-proof');

    // Tour Booking
    Route::get('/tour/{package:slug}/book', [TourController::class, 'bookingForm'])->name('tour.booking');
    Route::post('/tour/{package:slug}/book', [TourController::class, 'storeBooking'])->name('tour.store-booking');
    Route::get('/tour/checkout/{booking}', [TourController::class, 'checkout'])->name('tour.checkout');

    // Review
    Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');
});

// ─────────────────────────────────────────────
// ADMIN
// ─────────────────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/notifications/read', [AdminDashboardController::class, 'markNotificationsRead'])->name('notifications.read');

        // CRUD
        Route::resource('buses', BusController::class);
        Route::resource('routes', RouteController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::resource('tour-packages', TourPackageController::class);
        Route::resource('flash-sales', FlashSaleController::class);

        // Transactions
        Route::get('/transactions/bookings', [TransactionController::class, 'bookings'])->name('transactions.bookings');
        Route::get('/transactions/rentals', [TransactionController::class, 'rentals'])->name('transactions.rentals');
        Route::get('/transactions/tours', [TransactionController::class, 'tours'])->name('transactions.tours');
        Route::get('/transactions/tours/{booking}', [TransactionController::class, 'tourShow'])->name('transactions.tours.show');
        Route::get('/transactions/payments', [TransactionController::class, 'payments'])->name('transactions.payments');

        // Approval
        Route::post('/rental/{rental}/approve', [TransactionController::class, 'approveRental'])->name('rental.approve');
        Route::post('/rental/{rental}/reject', [TransactionController::class, 'rejectRental'])->name('rental.reject');

        // Manual Payment
        Route::patch('/booking/{booking}/status', [TransactionController::class, 'updateBookingStatus'])->name('booking.status');
        Route::post('/booking/{booking}/approve-manual', [TransactionController::class, 'approveManualBookingPayment'])->name('booking.approve-manual');
        Route::post('/rental/{rental}/approve-manual', [TransactionController::class, 'approveManualRentalPayment'])->name('rental.approve-manual');
        Route::post('/tour/{booking}/approve-manual', [TransactionController::class, 'approveManualTourPayment'])->name('tour.approve-manual');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');

        // Ticket Scanner
        Route::get('/ticket/scan', [TicketController::class, 'scanPage'])->name('ticket.scan');
        Route::post('/ticket/scan/result', [TicketController::class, 'scanResult'])->name('ticket.scan.result');
    });

// Test Broadcast
Route::get('/test-broadcast', function () {
    event(new \App\Events\TestEvent('Hello from Reverb!'));
    return 'Event broadcasted!';
});

Route::get('/reverb-test-page', function () {
    return view('test-reverb');
});

// Simulasi Payment Broadcast (untuk demo/screenshot laporan)
Route::get('/test-payment-broadcast/{payment_id}', function ($payment_id) {
    $payment = \App\Models\Payment::findOrFail($payment_id);
    broadcast(new \App\Events\PaymentStatusUpdated($payment, 'settlement'));
    return response()->json([
        'status' => 'ok',
        'message' => "PaymentStatusUpdated broadcasted untuk payment ID: {$payment_id}",
        'channel' => "payment.{$payment_id}",
    ]);
});