<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('target_type'); // 'tour_package', 'schedule', 'rental'
            $table->unsignedBigInteger('target_id');
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('discount_value', 12, 2);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('quota')->default(0);
            $table->integer('used_quota')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
