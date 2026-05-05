<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('promo_banner_id')->nullable()->constrained()->onDelete('set null')->after('schedule_id');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('total_price');
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->foreignId('promo_banner_id')->nullable()->constrained()->onDelete('set null')->after('bus_id');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('total_price');
        });

        Schema::table('tour_bookings', function (Blueprint $table) {
            $table->foreignId('promo_banner_id')->nullable()->constrained()->onDelete('set null')->after('tour_package_id');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('promo_banner_id');
            $table->dropColumn('discount_amount');
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('promo_banner_id');
            $table->dropColumn('discount_amount');
        });

        Schema::table('tour_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('promo_banner_id');
            $table->dropColumn('discount_amount');
        });
    }
};
