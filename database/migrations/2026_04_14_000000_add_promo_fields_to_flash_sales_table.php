<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('title');
            $table->text('description')->nullable()->after('banner_image');
            $table->text('terms_conditions')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'description', 'terms_conditions']);
        });
    }
};
