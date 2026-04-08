@extends('layouts.app')
@section('title', 'Dashboard - Bus 88')

@push('styles')
<style>
    /* ============================================================
       CSS VARIABLES
    ============================================================ */
    :root {
        --primary:       #cc0000;
        --primary-light: #ff4444;
        --primary-dark:  #990000;
        --accent:        #ffd700;
        --dark:          #1a1a1a;
        --gray-light:    #f5f5f5;
        --gray-medium:   #757575;
        --white:         #ffffff;
    }

    /* ============================================================
       BASE TYPOGRAPHY
    ============================================================ */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    h1, h2, h3 {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        letter-spacing: -0.5px;
    }

    /* ============================================================
       GRADIENT HEADER
    ============================================================ */
    .gradient-header {
        background: linear-gradient(135deg, #cc0000 0%, #ff4444 50%, #ffe0e0 100%);
        position: relative;
        overflow: hidden;
    }

    .gradient-header::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .gradient-header::after {
        content: '';
        position: absolute;
        bottom: -30%; left: -5%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(0,0,0,0.05) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* ============================================================
       PROFILE CARD
    ============================================================ */
    .profile-card {
        background: linear-gradient(135deg, var(--white) 0%, #fafafa 100%);
        border: 1px solid #efefef;
        border-radius: 20px;
        padding: 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(204,0,0,0.05) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* Avatar */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .avatar-wrapper img {
        width: 100px; height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--primary);
        box-shadow: 0 8px 24px rgba(204,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .avatar-wrapper:hover img { transform: scale(1.05); }

    .avatar-status {
        position: absolute;
        bottom: 0; right: 0;
        width: 28px; height: 28px;
        background: #10b981;
        border: 3px solid var(--white);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        50%       { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    }

    /* Profile info */
    .profile-info h2 {
        font-size: 24px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .profile-info p {
        color: var(--gray-medium);
        font-size: 14px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: var(--white);
        box-shadow: 0 6px 20px rgba(204,0,0,0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(204,0,0,0.4);
    }

    .btn-secondary-custom {
        background: var(--white);
        color: var(--primary);
        border: 2px solid var(--primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .btn-secondary-custom:hover {
        background: linear-gradient(135deg, rgba(204,0,0,0.05) 0%, rgba(204,0,0,0.02) 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    /* ============================================================
       SECTION HEADER
    ============================================================ */
    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        position: relative;
        padding-bottom: 16px;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0;
        width: 40px; height: 3px;
        background: linear-gradient(90deg, var(--primary), transparent);
        border-radius: 2px;
    }

    .section-header h2 {
        font-size: 22px;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
    }

    .section-icon {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, rgba(204,0,0,0.15) 0%, rgba(255,68,68,0.1) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    /* ============================================================
       BOOKING / RENTAL CARDS
    ============================================================ */
    .booking-card {
        background: linear-gradient(135deg, var(--white) 0%, #fafafa 100%);
        border: 1.5px solid #efefef;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.35s cubic-bezier(0.23, 1, 0.320, 1);
        position: relative;
        overflow: hidden;
    }

    .booking-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0;
        height: 100%; width: 4px;
        background: linear-gradient(180deg, var(--primary), var(--primary-light));
        transform: scaleY(0);
        transform-origin: top;
        transition: transform 0.35s ease;
    }

    .booking-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(204,0,0,0.12), 0 4px 12px rgba(0,0,0,0.08);
        border-color: rgba(204,0,0,0.2);
    }

    .booking-card:hover::before { transform: scaleY(1); }

    .booking-code {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        color: var(--primary);
        text-transform: uppercase;
        background: rgba(204,0,0,0.08);
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .booking-card:hover .booking-code {
        background: rgba(204,0,0,0.15);
        color: var(--primary-dark);
    }

    .booking-route {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin: 12px 0 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .route-arrow { color: var(--primary); font-weight: 900; }

    .booking-meta {
        font-size: 13px;
        color: var(--gray-medium);
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin: 12px 0;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-icon {
        width: 16px; height: 16px;
        color: rgba(204,0,0,0.5);
    }

    /* ============================================================
       PRICE TAG
    ============================================================ */
    .price-tag {
        font-size: 24px;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ============================================================
       BADGES
    ============================================================ */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .badge-success { background: #d4edda; color: #155724; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-danger  { background: #f8d7da; color: #721c24; }
    .badge-info    { background: #d1ecf1; color: #0c5460; }
    .badge-gray    { background: #e9ecef; color: #495057; }

    /* ============================================================
       EXPIRY NOTICE
    ============================================================ */
    .expiry-notice {
        margin-top: 12px;
        padding: 10px 12px;
        background: rgba(255, 193, 7, 0.1);
        border-left: 3px solid #ffc107;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #856404;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        animation: slideIn 0.4s ease;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state {
        background: linear-gradient(135deg, #fafafa 0%, var(--white) 100%);
        border: 2px dashed #e0e0e0;
        border-radius: 16px;
        padding: 48px 32px;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.4;
    }

    .empty-state p {
        color: var(--gray-medium);
        margin-bottom: 24px;
        font-size: 15px;
    }

    /* ============================================================
       DETAIL LINK
    ============================================================ */
    .detail-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }

    .detail-link::after {
        content: '';
        position: absolute;
        bottom: -3px; left: 0;
        width: 0; height: 2px;
        background: var(--primary);
        transition: width 0.3s ease;
    }

    .detail-link:hover::after { width: 100%; }

    .detail-link:hover {
        color: var(--primary-dark);
        transform: translateX(4px);
    }

    /* ============================================================
       EDIT PROFILE FORM
    ============================================================ */
    .form-section {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .form-section.show { display: block; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-medium);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-group input {
        padding: 12px 16px;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(204,0,0,0.1);
    }

    /* ============================================================
       PAGINATION
    ============================================================ */
    .pagination {
        justify-content: center;
        margin-top: 32px;
        gap: 8px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        color: var(--gray-medium);
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .pagination a:hover,
    .pagination .active span {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
    }

    /* ============================================================
       NOTIFICATIONS
    ============================================================ */
    .notification-slide {
        position: fixed;
        top: 20px; right: 20px;
        z-index: 9999;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideInRight 0.3s ease;
        font-weight: 500;
    }

    /* ============================================================
       ANIMATIONS
    ============================================================ */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to   { transform: translateX(0);    opacity: 1; }
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .profile-card { padding: 24px; }
        .avatar-wrapper img { width: 80px; height: 80px; }
        .booking-card { padding: 20px; }
        .section-header h2 { font-size: 18px; }
        .price-tag { font-size: 20px; }
        .btn-group { flex-direction: column; }
        .btn-action { justify-content: center; }
    }
</style>
@endpush

@section('content')

{{-- ============================================================
     GRADIENT HEADER
============================================================ --}}
<div class="gradient-header relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">

        <div class="mb-8">
            <h1 style="font-size: 36px; font-weight: 900; color: white; margin: 0; letter-spacing: -1px;">
                Welcome Back, {{ auth()->user()->name }}! 👋
            </h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin-top: 8px;">
                Kelola perjalanan dan sewaan bus Anda dengan mudah
            </p>
        </div>

        <div class="btn-group">
            <a href="{{ route('home') }}#search" class="btn-action btn-primary-custom">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Beli Tiket
            </a>
            <a href="{{ route('rental.index') }}" class="btn-action btn-secondary-custom">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9.5c0 .83-.67 1.5-1.5 1.5S11 13.33 11 12.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5z"/>
                </svg>
                Sewa Bus
            </a>
        </div>

    </div>
</div>

{{-- ============================================================
     MAIN CONTENT
============================================================ --}}
<div class="bg-gradient-to-b from-gray-light to-white min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- ================== PROFILE CARD ================== --}}
        <div class="profile-card mb-16">

            {{-- Profile Overview --}}
            <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 24px; align-items: start;">

                {{-- Avatar --}}
                <div class="avatar-wrapper">
                    <img
                        src="{{ auth()->user()->avatar
                            ? asset('avatar/' . auth()->user()->avatar)
                            : 'https://ui-avatars.com/api/?name=' . auth()->user()->name . '&background=cc0000&color=fff' }}"
                        alt="{{ auth()->user()->name }}"
                    >
                    <div class="avatar-status"></div>
                </div>

                {{-- Info --}}
                <div class="profile-info">
                    <h2>{{ auth()->user()->name }}</h2>
                    <p>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ auth()->user()->email }}
                    </p>
                    <p>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948-.684l1.498-4.493a1 1 0 011.502-.684l1.498 4.493a1 1 0 00.948.684H19a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/>
                        </svg>
                        {{ auth()->user()->phone ?? 'Belum ditambahkan' }}
                    </p>
                    <p>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ auth()->user()->address ?? 'Belum ditambahkan' }}
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button onclick="toggleEditProfile()" class="btn-action btn-primary-custom">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit Profile
                    </button>
                    <button onclick="toggleChangePassword()" class="btn-action btn-secondary-custom">
                        🔒 Ubah Password
                    </button>
                </div>

            </div>

            {{-- ================== EDIT PROFILE FORM ================== --}}
            <form
                id="editProfileForm"
                method="POST"
                action="{{ route('profile.update') }}"
                enctype="multipart/form-data"
                class="form-section mt-8"
                style="border-top: 2px solid #f0f0f0; padding-top: 24px;"
            >
                @csrf
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--dark);">
                    Update Informasi Profil
                </h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ auth()->user()->phone }}" placeholder="08xx xxxx xxxx">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="address" value="{{ auth()->user()->address }}" placeholder="Jl. XX No. XX">
                    </div>
                    <div class="form-group">
                        <label>Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <input type="file" name="avatar" accept="image/*">
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary-custom">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="toggleEditProfile()" class="btn-action btn-secondary-custom">
                        Batal
                    </button>
                </div>
            </form>

            {{-- ================== OTP CHANGE PASSWORD ================== --}}
            <div id="otpSection" class="mt-6 p-4" style="display: none; background: #f9f9f9; border-radius: 12px; border: 1px solid #e0e0e0;">

                {{-- Step 1: Kirim OTP --}}
                <div id="step1SendOtp">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Step 1: Kirim Kode Verifikasi</h3>

                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input
                            type="email"
                            value="{{ auth()->user()->email }}"
                            disabled
                            style="background: #f5f5f5; width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #e0e0e0;"
                        >
                        <small style="color: #6c757d; font-size: 12px;">Kode OTP akan dikirim ke email ini</small>
                    </div>

                    <button type="button" id="sendOtpBtn" class="btn-action btn-primary-custom">
                        📧 Kirim OTP
                    </button>

                    <div id="timerDisplay" style="display: none; margin-top: 16px; padding: 10px; background: #fff3cd; border-radius: 8px; color: #856404;">
                        ⏳ Harap tunggu <span id="countdown">60</span> detik sebelum mengirim ulang OTP
                    </div>
                </div>

                {{-- Step 2: Verifikasi OTP --}}
                <div id="step2VerifyOtp" style="display: none;">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Step 2: Masukkan Kode Verifikasi</h3>

                    <div style="background: #d1ecf1; padding: 12px; border-radius: 8px; margin-bottom: 16px; color: #0c5460;">
                        📧 Kode OTP telah dikirim ke email Anda. Masukkan 6 digit kode di bawah ini.
                    </div>

                    <div class="form-group mb-3">
                        <label>Kode OTP</label>
                        <input
                            type="text"
                            id="otpCode"
                            style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 20px; letter-spacing: 8px; text-align: center;"
                            placeholder="123456"
                            maxlength="6"
                            autocomplete="off"
                        >
                        <small id="otpError" style="color: #dc3545; display: none; margin-top: 5px;"></small>
                    </div>

                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" id="verifyOtpBtn" class="btn-action btn-primary-custom">
                            ✅ Verifikasi OTP
                        </button>
                        <button type="button" id="backToSendBtn" class="btn-action btn-secondary-custom">
                            ← Kirim Ulang OTP
                        </button>
                    </div>
                </div>

                {{-- Step 3: Ganti Password --}}
                <div id="step3ChangePassword" style="display: none;">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #28a745;">
                        ✅ Step 3: Ganti Password
                    </h3>

                    <div style="background: #d4edda; padding: 12px; border-radius: 8px; margin-bottom: 16px; color: #155724;">
                        ✅ OTP berhasil diverifikasi! Silakan masukkan password baru Anda.
                        <small id="sessionTimer" style="display: block; margin-top: 5px;"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Password Baru</label>
                        <input
                            type="password"
                            id="newPassword"
                            style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e0e0e0;"
                            placeholder="Minimal 6 karakter"
                        >
                        <small id="passwordError" style="color: #dc3545; display: none;"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            id="confirmPassword"
                            style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e0e0e0;"
                            placeholder="Ketik ulang password baru"
                        >
                    </div>

                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" id="changePasswordBtn" class="btn-action btn-primary-custom">
                            💾 Simpan Password Baru
                        </button>
                        <button type="button" id="resetAllBtn" class="btn-action btn-secondary-custom">
                            ↻ Mulai Ulang
                        </button>
                    </div>
                </div>

            </div>{{-- #otpSection --}}

        </div>{{-- .profile-card --}}

        {{-- ================== BOOKING HISTORY ================== --}}
        <div class="mb-16">
            <div class="section-header">
                <div class="section-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8"  y1="2" x2="8"  y2="6"/>
                        <line x1="3"  y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <h2>Riwayat Tiket Bus</h2>
            </div>

            @if($bookings->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">🎫</div>
                    <p>Belum ada pemesanan tiket. Mulai perjalanan Anda sekarang!</p>
                    <a href="{{ route('home') }}" class="btn-action btn-primary-custom">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari Tiket Sekarang
                    </a>
                </div>
            @else
                <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                    @foreach($bookings as $booking)
                        @php
                            $statusClass = match($booking->payment_status) {
                                'paid'      => 'badge-success',
                                'pending'   => 'badge-warning',
                                'expired'   => 'badge-gray',
                                'cancelled' => 'badge-danger',
                                'refunded'  => 'badge-info',
                                default     => 'badge-gray',
                            };
                            $statusLabel = match($booking->payment_status) {
                                'paid'      => '✓ Lunas',
                                'pending'   => '⏳ Menunggu Bayar',
                                'expired'   => '⏱ Kedaluwarsa',
                                'cancelled' => '✗ Dibatalkan',
                                'refunded'  => '↩ Refund',
                                default     => $booking->payment_status,
                            };
                        @endphp

                        <div class="booking-card">
                            <div style="display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: start;">

                                <div>
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                        <span class="booking-code">{{ $booking->booking_code }}</span>
                                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                    </div>

                                    <div class="booking-route">
                                        {{ $booking->schedule->route->origin }}
                                        <span class="route-arrow">→</span>
                                        {{ $booking->schedule->route->destination }}
                                    </div>

                                    <div class="booking-meta">
                                        <div class="meta-item">
                                            <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $booking->schedule->departure_date->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="meta-item">
                                            <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }} WIB
                                        </div>
                                        <div class="meta-item">
                                            <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 14h8m-8-4h8M9 8a1 1 0 110-2h6a1 1 0 110 2H9z"/>
                                            </svg>
                                            {{ $booking->total_seats }} kursi
                                        </div>
                                    </div>

                                    @if($booking->payment_status === 'pending' && $booking->expired_at)
                                        <div class="expiry-notice">
                                            ⏰ Bayar sebelum {{ $booking->expired_at->format('H:i') }} WIB
                                        </div>
                                    @endif
                                </div>

                                <div style="text-align: right; display: flex; flex-direction: column; gap: 12px; align-items: flex-end;">
                                    <div class="price-tag">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>

                                    @if($booking->payment_status === 'pending' && $booking->snap_token)
                                        <a href="{{ route('booking.checkout', $booking) }}" class="btn-action btn-primary-custom">
                                            Bayar Sekarang
                                        </a>
                                    @endif

                                    <a href="{{ route('dashboard.booking', $booking) }}" class="detail-link">
                                        Lihat Detail
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $bookings->links() }}
            @endif
        </div>

        {{-- ================== RENTAL HISTORY ================== --}}
        <div class="mb-16">
            <div class="section-header">
                <div class="section-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"/>
                        <polyline points="9 7 9 17 15 17 15 7"/>
                    </svg>
                </div>
                <h2>Riwayat Sewa Bus</h2>
            </div>

            @if($rentals->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">🚌</div>
                    <p>Belum ada pesanan sewa bus. Sewa bus untuk acara Anda sekarang!</p>
                    <a href="{{ route('rental.index') }}" class="btn-action btn-primary-custom">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Mulai Sewa Bus
                    </a>
                </div>
            @else
                <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                    @foreach($rentals as $rental)
                        @php
                            $approvalClass = match($rental->approval_status) {
                                'approved' => 'badge-success',
                                'pending'  => 'badge-warning',
                                'rejected' => 'badge-danger',
                                default    => 'badge-gray',
                            };
                            $approvalLabel = match($rental->approval_status) {
                                'approved' => '✓ Disetujui',
                                'pending'  => '⏳ Menunggu',
                                'rejected' => '✗ Ditolak',
                                default    => ucfirst($rental->approval_status),
                            };
                        @endphp

                        <div class="booking-card">
                            <div style="display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: start;">

                                <div>
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                        <span class="booking-code">{{ $rental->rental_code }}</span>
                                        <span class="badge {{ $approvalClass }}">{{ $approvalLabel }}</span>
                                    </div>

                                    <div class="booking-route">
                                        {{ $rental->pickup_location }}
                                        <span class="route-arrow">→</span>
                                        {{ $rental->destination }}
                                    </div>

                                    <div class="booking-meta">
                                        <div class="meta-item">
                                            <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $rental->start_date->translatedFormat('d M Y') }} - {{ $rental->end_date->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="meta-item">
                                            <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $rental->duration_days }} hari
                                        </div>
                                        @if($rental->bus)
                                            <div class="meta-item">
                                                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"/>
                                                </svg>
                                                {{ $rental->bus->name }}
                                            </div>
                                        @endif
                                    </div>

                                    @if($rental->approval_status === 'approved' && in_array($rental->payment_status, ['unpaid', 'pending']))
                                        <div class="expiry-notice">
                                            ⏰ Bayar sebelum {{ $rental->updated_at->addHours(2)->format('H:i') }} WIB
                                        </div>
                                    @endif
                                </div>

                                <div style="text-align: right; display: flex; flex-direction: column; gap: 12px; align-items: flex-end;">
                                    @if($rental->total_price)
                                        <div class="price-tag">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
                                    @else
                                        <p style="color: var(--gray-medium); font-size: 13px;">Harga belum ditentukan</p>
                                    @endif

                                    @if($rental->approval_status === 'approved' && $rental->payment_status !== 'paid')
                                        <a href="{{ route('rental.pay', $rental) }}" class="btn-action btn-primary-custom">
                                            Bayar Sekarang
                                        </a>
                                    @endif

                                    <a href="{{ route('dashboard.rental', $rental) }}" class="detail-link">
                                        Lihat Detail
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $rentals->links() }}
            @endif
        </div>

        {{-- ================== TOUR PACKAGE HISTORY ================== --}}
        <div class="mb-16">
            <div class="section-header">
                <div class="section-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/>
                    </svg>
                </div>
                <h2>Riwayat Paket Wisata</h2>
            </div>

            @if($tourBookings->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">🌍</div>
                    <p>Belum ada pesanan paket wisata. Jelajahi destinasi impian Anda sekarang!</p>
                    <a href="{{ route('tour.index') }}" class="btn-action btn-primary-custom">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2a15.3 15.3 0 0110 4M12 14h.01"/>
                        </svg>
                        Cari Paket Wisata
                    </a>
                </div>
            @else
                <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                    @foreach($tourBookings as $tBooking)
                        @php
                            $tStatusClass = match($tBooking->payment_status) {
                                'paid'    => 'badge-success',
                                'pending' => 'badge-warning',
                                default   => 'badge-gray',
                            };
                            $tStatusLabel = match($tBooking->payment_status) {
                                'paid'    => '✓ Lunas',
                                'pending' => '⏳ Menunggu',
                                default   => ucfirst($tBooking->payment_status),
                            };
                        @endphp

                        <div class="booking-card">
                            <div style="display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: start;">

                                <div>
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                        <span class="booking-code">{{ $tBooking->booking_code }}</span>
                                        <span class="badge {{ $tStatusClass }}">{{ $tStatusLabel }}</span>
                                    </div>

                                    <div class="booking-route">
                                        {{ $tBooking->tourPackage->name ?? 'Paket Tidak Tersedia' }}
                                    </div>

                                    <div class="booking-meta">
                                        <div class="meta-item">
                                            <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $tBooking->travel_date ? $tBooking->travel_date->format('d M Y') : '-' }}
                                        </div>
                                        <div class="meta-item">
                                            <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                            {{ $tBooking->passenger_count }} Orang
                                        </div>
                                    </div>

                                    @if($tBooking->payment_status === 'pending')
                                        <div class="expiry-notice">
                                            ⏰ Bayar sebelum {{ $tBooking->created_at->addHours(2)->format('H:i') }} WIB
                                        </div>
                                    @endif
                                </div>

                                <div style="text-align: right; display: flex; flex-direction: column; gap: 12px; align-items: flex-end;">
                                    <div class="price-tag">Rp {{ number_format($tBooking->total_price, 0, ',', '.') }}</div>

                                    @if($tBooking->payment_status === 'pending')
                                        <a href="{{ route('tour.checkout', $tBooking) }}" class="btn-action btn-primary-custom">
                                            Bayar Sekarang
                                        </a>
                                    @endif

                                    <a href="{{ route('dashboard.tour', $tBooking) }}" class="detail-link">
                                        Lihat Detail
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $tourBookings->links() }}
            @endif
        </div>

    </div>
</div>

@push('scripts')
<script>
// ============================================================
// VARIABLES
// ============================================================
let countdownInterval = null;
let statusCheckInterval = null;

// ============================================================
// UTILITY FUNCTIONS
// ============================================================
function showNotification(message, type = 'success') {
    const colors = {
        success: { bg: '#d4edda', text: '#155724', border: '#c3e6cb' },
        error:   { bg: '#f8d7da', text: '#721c24', border: '#f5c6cb' },
    };
    const c = colors[type] ?? colors.success;

    const el = document.createElement('div');
    el.className = 'notification-slide';
    el.style.cssText = `background:${c.bg}; color:${c.text}; border:1px solid ${c.border};`;
    el.innerHTML = message;
    document.body.appendChild(el);

    setTimeout(() => el.remove(), 5000);
}

function clearTimer() {
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }

    const timerDisplay = document.getElementById('timerDisplay');
    const sendBtn      = document.getElementById('sendOtpBtn');

    if (timerDisplay) timerDisplay.style.display = 'none';
    if (sendBtn) {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '📧 Kirim OTP';
    }
}

function startCooldown(seconds) {
    clearTimer();

    let remaining = Math.min(seconds, 60);

    const timerDisplay  = document.getElementById('timerDisplay');
    const countdownSpan = document.getElementById('countdown');
    const sendBtn       = document.getElementById('sendOtpBtn');

    if (!timerDisplay || !countdownSpan || !sendBtn) return;

    timerDisplay.style.display = 'block';
    sendBtn.disabled = true;
    countdownSpan.textContent = remaining;

    countdownInterval = setInterval(() => {
        remaining--;
        countdownSpan.textContent = remaining;
        sendBtn.innerHTML = `⏳ Tunggu ${remaining} detik...`;

        if (remaining <= 0) {
            clearTimer();
            sendBtn.innerHTML = '📧 Kirim OTP';
        }
    }, 1000);
}

function resetToStep1() {
    const ids = {
        step1: document.getElementById('step1SendOtp'),
        step2: document.getElementById('step2VerifyOtp'),
        step3: document.getElementById('step3ChangePassword'),
    };

    if (ids.step1) ids.step1.style.display = 'block';
    if (ids.step2) ids.step2.style.display = 'none';
    if (ids.step3) ids.step3.style.display = 'none';

    ['otpCode', 'newPassword', 'confirmPassword'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.value = ''; el.style.borderColor = ''; }
    });

    ['otpError', 'passwordError'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    clearTimer();
}

// ============================================================
// TOGGLE FUNCTIONS
// ============================================================
function toggleEditProfile() {
    document.getElementById('editProfileForm')?.classList.toggle('show');
}

function toggleChangePassword() {
    const otpSection = document.getElementById('otpSection');
    if (!otpSection) return;

    if (otpSection.style.display === 'none') {
        otpSection.style.display = 'block';
        resetToStep1();
    } else {
        otpSection.style.display = 'none';
        resetToStep1();
        if (statusCheckInterval) clearInterval(statusCheckInterval);
    }
}

// ============================================================
// STEP 1 — SEND OTP
// ============================================================
document.getElementById('sendOtpBtn')?.addEventListener('click', async function () {
    const originalText = this.innerHTML;
    this.disabled = true;
    this.innerHTML = '⏳ Mengirim OTP...';

    try {
        const res  = await fetch('{{ route("password.otp.send") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({}),
        });
        const data = await res.json();

        if (data.success) {
            showNotification(data.message, 'success');
            document.getElementById('step1SendOtp').style.display = 'none';
            document.getElementById('step2VerifyOtp').style.display = 'block';
            document.getElementById('otpCode')?.focus();
            startCooldown(60);
        } else {
            showNotification(data.message, 'error');
            this.disabled = false;
            this.innerHTML = originalText;
            if (data.remaining) startCooldown(data.remaining);
        }
    } catch (err) {
        console.error(err);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        this.disabled = false;
        this.innerHTML = originalText;
    }
});

// ============================================================
// STEP 2 — VERIFY OTP
// ============================================================
document.getElementById('verifyOtpBtn')?.addEventListener('click', async function () {
    const otp = document.getElementById('otpCode')?.value.trim() ?? '';

    if (otp.length !== 6) {
        showNotification('OTP harus 6 digit!', 'error');
        const otpInput = document.getElementById('otpCode');
        if (otpInput) otpInput.style.borderColor = '#dc3545';
        return;
    }

    const originalText = this.innerHTML;
    this.disabled = true;
    this.innerHTML = '⏳ Memverifikasi...';

    try {
        const res  = await fetch('{{ route("password.otp.verify.only") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ otp }),
        });
        const data = await res.json();

        if (data.success) {
            showNotification(data.message, 'success');
            document.getElementById('step2VerifyOtp').style.display = 'none';
            document.getElementById('step3ChangePassword').style.display = 'block';
            document.getElementById('newPassword')?.focus();

            if (statusCheckInterval) clearInterval(statusCheckInterval);
            statusCheckInterval = setInterval(checkVerificationStatus, 1000);
        } else {
            showNotification(data.message, 'error');
            const otpInput = document.getElementById('otpCode');
            const otpError = document.getElementById('otpError');
            if (otpInput) otpInput.style.borderColor = '#dc3545';
            if (otpError) { otpError.textContent = data.message; otpError.style.display = 'block'; }

            setTimeout(() => {
                if (otpInput) otpInput.style.borderColor = '';
                if (otpError) otpError.style.display = 'none';
            }, 3000);
        }
    } catch (err) {
        console.error(err);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
    } finally {
        this.disabled = false;
        this.innerHTML = originalText;
    }
});

// ============================================================
// STEP 3 — CHANGE PASSWORD
// ============================================================
document.getElementById('changePasswordBtn')?.addEventListener('click', async function () {
    const password        = document.getElementById('newPassword')?.value    ?? '';
    const confirmPassword = document.getElementById('confirmPassword')?.value ?? '';
    const passwordError   = document.getElementById('passwordError');

    if (password.length < 6) {
        showNotification('Password minimal 6 karakter!', 'error');
        const el = document.getElementById('newPassword');
        if (el) el.style.borderColor = '#dc3545';
        if (passwordError) { passwordError.textContent = 'Password minimal 6 karakter'; passwordError.style.display = 'block'; }
        return;
    }

    if (password !== confirmPassword) {
        showNotification('Password dan konfirmasi tidak sama!', 'error');
        const el = document.getElementById('confirmPassword');
        if (el) el.style.borderColor = '#dc3545';
        if (passwordError) { passwordError.textContent = 'Password tidak sama'; passwordError.style.display = 'block'; }
        return;
    }

    const originalText = this.innerHTML;
    this.disabled = true;
    this.innerHTML = '⏳ Menyimpan...';

    try {
        const res  = await fetch('{{ route("password.change") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ password, password_confirmation: confirmPassword }),
        });
        const data = await res.json();

        if (data.success) {
            showNotification(data.message + ' Anda akan logout.', 'success');
            setTimeout(() => {
                const form = Object.assign(document.createElement('form'), {
                    method: 'POST',
                    action: '{{ route("logout") }}',
                    style:  'display:none',
                });
                const csrf = Object.assign(document.createElement('input'), {
                    name:  '_token',
                    value: '{{ csrf_token() }}',
                });
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }, 2000);
        } else {
            showNotification(data.message, 'error');
            if (data.message?.includes('verifikasi')) resetToStep1();
        }
    } catch (err) {
        console.error(err);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
    } finally {
        this.disabled = false;
        this.innerHTML = originalText;
    }
});

// ============================================================
// CHECK VERIFICATION SESSION STATUS
// ============================================================
async function checkVerificationStatus() {
    try {
        const res  = await fetch('{{ route("password.check.status") }}', {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        });
        const data = await res.json();
        const sessionTimer = document.getElementById('sessionTimer');

        if (data.verified) {
            if (sessionTimer && data.expires_in > 0) {
                const m = Math.floor(data.expires_in / 60);
                const s = data.expires_in % 60;
                sessionTimer.innerHTML = `⏰ Sesi verifikasi berlaku ${m}:${String(s).padStart(2, '0')} menit`;
            }
        } else {
            if (sessionTimer) sessionTimer.innerHTML = '';
            resetToStep1();
            if (statusCheckInterval) clearInterval(statusCheckInterval);
            showNotification('Sesi verifikasi berakhir. Silakan mulai dari awal.', 'error');
        }
    } catch (err) {
        console.error('Error checking status:', err);
    }
}

// ============================================================
// BACK / RESET BUTTONS
// ============================================================
document.getElementById('backToSendBtn')?.addEventListener('click', resetToStep1);
document.getElementById('resetAllBtn')?.addEventListener('click', () => {
    resetToStep1();
    if (statusCheckInterval) clearInterval(statusCheckInterval);
});

// ============================================================
// INPUT VALIDATION
// ============================================================
document.getElementById('otpCode')?.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 6);
    this.style.borderColor = '';
    const otpError = document.getElementById('otpError');
    if (otpError) otpError.style.display = 'none';
});

['newPassword', 'confirmPassword'].forEach(id => {
    document.getElementById(id)?.addEventListener('focus', function () {
        this.style.borderColor = '';
        const passwordError = document.getElementById('passwordError');
        if (passwordError) passwordError.style.display = 'none';
    });
});
</script>
@endpush

@endsection