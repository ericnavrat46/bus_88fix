<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->integer('duration_days');
            $table->decimal('price_per_person', 12, 2);
            $table->string('image')->nullable();
            $table->text('destinations'); // List of tourist spots
            $table->text('inclusions')->nullable(); // What's included
            $table->text('exclusions')->nullable(); // What's not included
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_packages');
    }
};
