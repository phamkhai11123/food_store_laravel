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
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->enum('type', ['import', 'export', 'loss']);
            $table->decimal('quantity_base', 18, 3); // dương khi nhập, âm khi xuất/hao hụt
            $table->string('unit', 10); // g, ml, pc
            $table->decimal('unit_cost', 18, 2)->nullable(); // giá vốn tại thời điểm
            $table->unsignedBigInteger('ref_id')->nullable(); // có thể là order_id hoặc import_id
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
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
