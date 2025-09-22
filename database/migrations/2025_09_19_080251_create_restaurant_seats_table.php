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
        Schema::create('restaurant_seats', function (Blueprint $table) {
            $table->id();
            $table->integer('total_seats')->default(80);     // Tổng số ghế của quán
            $table->integer('available_seats')->default(80); // Ghế còn trống
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_seats');
    }
};
