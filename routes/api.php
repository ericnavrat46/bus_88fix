<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mobile\AuthController;
use App\Http\Controllers\Api\Mobile\ProfileController;
use App\Http\Controllers\Api\Mobile\SeatController;
use App\Http\Controllers\Api\Mobile\BookingController;
use App\Http\Controllers\Api\Mobile\TourController;
use App\Http\Controllers\Api\Mobile\TourBookingController;
use App\Http\Controllers\Api\Mobile\RentalController;
use App\Http\Controllers\Api\Mobile\BusController;
use App\Http\Controllers\Api\Mobile\PromoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\Mobile\NotificationController;



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
Route::post('/booking/cancel/{id}', [BookingController::class, 'cancel']);
Route::get('/tour-packages', [TourController::class, 'index']);
Route::post('/tour-bookings', [TourBookingController::class, 'store']);
Route::get('/my-tour-bookings/{user_id}', [TourBookingController::class, 'myBookings']);
Route::post('/cancel-tour-booking/{id}', [TourBookingController::class, 'cancel']);
Route::post('/upload-payment-tour', [TourBookingController::class, 'uploadPayment']);
Route::post('/upload-payment-rental', [RentalController::class, 'uploadPayment']);
Route::get('/my-rentals/{user_id}', [RentalController::class, 'myRentals']);
Route::post('/cancel-rental/{id}', [RentalController::class, 'cancel']);
Route::post('/finish-booking/{id}', [BookingController::class, 'finish']);
Route::post('/finish-tour/{id}', [TourBookingController::class, 'finish']);
Route::post('/finish-rental/{id}', [RentalController::class, 'finish']);
Route::get('/buses', [BusController::class, 'index']);
Route::get('/buses/{id}', [BusController::class, 'show']);
Route::post('/rentals/store', [RentalController::class, 'store']);
Route::get('/promo/active', [PromoController::class, 'getActivePromo']);
Route::post('/payments/midtrans', [PaymentController::class, 'create']);
Route::post('/payments/midtrans/notification', [PaymentController::class, 'notification']);
Route::get('/payments/check/{bookingId}', [PaymentController::class, 'checkStatus']);
Route::get('/payments/sync/{bookingId}', [PaymentController::class, 'syncStatus']);
Route::get('/promo/active', [PromoController::class, 'getActivePromo']);
Route::post('/promo/detail',  [PromoController::class, 'getPromoDetail']);
Route::post('/promo/apply',   [PromoController::class, 'applyPromo']);
Route::post('/promo/confirm', [PromoController::class, 'confirmPromo']);
Route::post('/save-fcm-token', [AuthController::class, 'saveFcmToken']);
Route::post('/test-notif', [AuthController::class, 'testNotif']);
Route::post('/tour-bookings/confirm-payment', [TourBookingController::class, 'confirmPayment']);
Route::post('/confirm-rental-payment', [RentalController::class, 'confirmPayment']);

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


// 🔔 NOTIFIKASI
// urutan penting: /unread-count dan /read-all harus sebelum /{id}
Route::get('/notifications/{user_id}',              [NotificationController::class, 'index']);
Route::get('/notifications/{user_id}/unread-count', [NotificationController::class, 'unreadCount']);
Route::post('/notifications/{user_id}/read-all',    [NotificationController::class, 'markAllAsRead']);
Route::post('/notifications/{id}/read',             [NotificationController::class, 'markAsRead']);
Route::delete('/notifications/{id}',                [NotificationController::class, 'destroy']);