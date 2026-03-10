<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('rental_code', 20)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bus_id')->nullable()->constrained()->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_days');
            $table->string('pickup_location');
            $table->string('destination');
            $table->text('purpose')->nullable();
            $table->integer('passenger_count')->nullable();
            $table->string('contact_name');
            $table->string('contact_phone', 20);
            $table->decimal('total_price', 12, 2)->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'expired', 'cancelled'])->default('unpaid');
            $table->string('snap_token')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
