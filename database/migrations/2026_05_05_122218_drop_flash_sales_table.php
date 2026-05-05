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
        Schema::dropIfExists('flash_sales');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->morphs('target'); // schedule or tour_package
            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->decimal('discount_value', 15, 2);
            $table->integer('quota');
            $table->integer('used_quota')->default(0);
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
