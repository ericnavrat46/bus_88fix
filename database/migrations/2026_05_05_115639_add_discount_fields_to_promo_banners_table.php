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
        Schema::table('promo_banners', function (Blueprint $table) {
            $table->enum('target_type', ['all', 'ticket', 'rental', 'tour'])->default('all')->after('link');
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent')->after('target_type');
            $table->decimal('discount_value', 15, 2)->nullable()->after('discount_type');
            $table->decimal('min_transaction', 15, 2)->nullable()->after('discount_value');
            $table->decimal('max_discount', 15, 2)->nullable()->after('min_transaction');
            $table->integer('quota')->default(0)->after('max_discount');
            $table->integer('used_quota')->default(0)->after('quota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_banners', function (Blueprint $table) {
            $table->dropColumn([
                'target_type',
                'discount_type',
                'discount_value',
                'min_transaction',
                'max_discount',
                'quota',
                'used_quota'
            ]);
        });
    }
};
