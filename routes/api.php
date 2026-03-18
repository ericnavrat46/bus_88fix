<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mobile\AuthController;
use App\Http\Controllers\Api\Mobile\ProfileController;


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