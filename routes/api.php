<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mobile\AuthController;
use App\Http\Controllers\Api\Mobile\ProfileController;
use App\Http\Controllers\Api\Mobile\SeatController;
use App\Http\Controllers\Api\Mobile\BookingController;
use App\Http\Controllers\Api\Mobile\TourController;
use App\Http\Controllers\Api\Mobile\TourBookingController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/profile/{id}', [ProfileController::class, 'profile']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);
Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar']);
Route::post('/update-name', [ProfileController::class, 'updateName']);
Route::post('/update-password', [ProfileController::class, 'updatePassword']);
Route::post('/send-otp', [ProfileController::class, 'sendOtp']);
Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
Route::post('/update-phone', [ProfileController::class, 'updatePhone']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp-reset', [AuthController::class, 'verifyOtpReset']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/seat-layout/{schedule_id}', [SeatController::class, 'getSeatLayout']);
Route::post('/book-seats', [SeatController::class, 'bookSeats']);
Route::get('/schedules', [SeatController::class, 'getSchedules']);
Route::get('/my-bookings/{user_id}', [BookingController::class,'myBookings']);
Route::get('/booking-detail/{booking_id}', [BookingController::class,'bookingDetail']);
Route::post('/upload-payment', [BookingController::class,'uploadPayment']);
Route::get('/tour-packages', [TourController::class, 'index']);
Route::post('/tour-bookings', [TourBookingController::class, 'store']);


// TEST EMAIL
Route::get('/test-email', function () {
    \Mail::raw('Ini test email dari Laravel', function ($message) {
        $message->to('test@mail.com')
                ->subject('Test Email');
    });

    return "Email terkirim!";
});

// 🔐 OTP
Route::post('/send-otp', [ProfileController::class, 'sendOtp']);
Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
