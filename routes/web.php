<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'searchSchedules'])->name('schedules.search');

// Tour Public Routes
Route::get('/tour', [TourController::class, 'index'])->name('tour.index');
Route::get('/tour/{package:slug}', [TourController::class, 'show'])->name('tour.show');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Google OAuth (no guest middleware — handled inside controller)
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Midtrans Webhook (no auth)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

// Customer Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/booking/{booking}', [DashboardController::class, 'bookingDetail'])->name('dashboard.booking');
    Route::get('/dashboard/rental/{rental}', [DashboardController::class, 'rentalDetail'])->name('dashboard.rental');

    // Booking Flow
    Route::get('/booking/{schedule}/select-seat', [BookingController::class, 'selectSeat'])->name('booking.select-seat');
    Route::post('/booking/{schedule}/passenger-form', [BookingController::class, 'passengerForm'])->name('booking.passenger-form');
    Route::post('/booking/{schedule}/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');

    // Rental / Charter
    Route::get('/charter', [RentalController::class, 'index'])->name('rental.index');
    Route::post('/charter', [RentalController::class, 'store'])->name('rental.store');
    Route::get('/charter/{rental}/pay', [RentalController::class, 'pay'])->name('rental.pay');

    // Payment Proof Uploads
    Route::post('/booking/{booking}/upload-proof', [PaymentProofController::class, 'uploadBookingProof'])->name('booking.upload-proof');
    Route::post('/rental/{rental}/upload-proof', [PaymentProofController::class, 'uploadRentalProof'])->name('rental.upload-proof');
    Route::post('/tour/{booking}/upload-proof', [PaymentProofController::class, 'uploadTourProof'])->name('tour.upload-proof');

    // Tour Booking Flow
    Route::get('/tour/{package:slug}/book', [TourController::class, 'bookingForm'])->name('tour.booking');
    Route::post('/tour/{package:slug}/book', [TourController::class, 'storeBooking'])->name('tour.store-booking');
    Route::get('/tour/checkout/{booking}', [TourController::class, 'checkout'])->name('tour.checkout');
    Route::get('/dashboard/tour/{booking}', [DashboardController::class, 'tourDetail'])->name('dashboard.tour');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD
        Route::resource('buses', BusController::class);
        Route::resource('routes', RouteController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::resource('tour-packages', TourPackageController::class);

        // Transactions
        Route::get('/transactions/bookings', [TransactionController::class, 'bookings'])->name('transactions.bookings');
        Route::get('/transactions/rentals', [TransactionController::class, 'rentals'])->name('transactions.rentals');
        Route::get('/transactions/tours', [TransactionController::class, 'tours'])->name('transactions.tours');
        Route::get('/transactions/tours/{booking}', [TransactionController::class, 'tourShow'])->name('transactions.tours.show');
        Route::get('/transactions/payments', [TransactionController::class, 'payments'])->name('transactions.payments');

        // Rental Approval
        Route::post('/rental/{rental}/approve', [TransactionController::class, 'approveRental'])->name('rental.approve');
        Route::post('/rental/{rental}/reject', [TransactionController::class, 'rejectRental'])->name('rental.reject');

        // Manual Status Update
        Route::patch('/booking/{booking}/status', [TransactionController::class, 'updateBookingStatus'])->name('booking.status');
        Route::post('/booking/{booking}/approve-manual', [TransactionController::class, 'approveManualBookingPayment'])->name('booking.approve-manual');
        Route::post('/rental/{rental}/approve-manual', [TransactionController::class, 'approveManualRentalPayment'])->name('rental.approve-manual');
        Route::post('/tour/{booking}/approve-manual', [TransactionController::class, 'approveManualTourPayment'])->name('tour.approve-manual');

        // Laporan
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    });
