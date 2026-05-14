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
        Schema::create('popular_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('price_display')->nullable()->comment('e.g. Rp 150k');
            $table->string('duration_display')->nullable()->comment('e.g. 2,5 Jam');
            $table->string('class_display')->nullable()->comment('e.g. Eksekutif');
            $table->string('badge_text')->nullable()->comment('e.g. PENAWARAN MENARIK');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popular_routes');
    }
};
