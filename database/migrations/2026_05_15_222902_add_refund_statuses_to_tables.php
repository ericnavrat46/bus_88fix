<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'expired', 'cancelled', 'refunded', 'pending_refund') DEFAULT 'pending'");
        DB::statement("ALTER TABLE rentals MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'paid', 'expired', 'cancelled', 'refunded', 'pending_refund') DEFAULT 'unpaid'");
        DB::statement("ALTER TABLE tour_bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'expired', 'cancelled', 'refunded', 'pending_refund') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'expired', 'cancelled', 'refunded') DEFAULT 'pending'");
        DB::statement("ALTER TABLE rentals MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'paid', 'expired', 'cancelled') DEFAULT 'unpaid'");
        DB::statement("ALTER TABLE tour_bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'expired', 'cancelled') DEFAULT 'pending'");
    }
};
