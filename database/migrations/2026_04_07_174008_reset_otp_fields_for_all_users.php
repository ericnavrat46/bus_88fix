<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Reset semua nilai OTP yang bermasalah
        DB::table('users')->update([
            'last_otp_sent_at' => null,
            'otp' => null,
            'expired_otp' => null
        ]);
    }

    public function down(): void
    {
        // Tidak perlu rollback
    }
};