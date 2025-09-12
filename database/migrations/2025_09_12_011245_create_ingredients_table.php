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
       Schema::create('ingredients', function (Blueprint $table) {
            $table->id(); 
            $table->string('sku')->nullable();
            $table->string('name'); 
            $table->enum('base_unit',['g','ml','pc']); 
            $table->decimal('suggested_unit_cost',12,4)->default(0); 
            $table->boolean('track_stock')->default(true)->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
