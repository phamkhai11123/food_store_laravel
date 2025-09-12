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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->enum('trx_type', ['purchase', 'sale', 'adjustment']);
            $table->string('ref_table', 32); // 'purchases' | 'orders'
            $table->unsignedBigInteger('ref_id');
            $table->decimal('quantity_base', 18, 3); // dương khi nhập, âm khi bán/điều chỉnh
            $table->dateTime('performed_at');
            $table->string('note', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
