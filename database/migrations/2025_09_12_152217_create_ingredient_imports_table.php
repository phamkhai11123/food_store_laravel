<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ingredient_imports', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã phiếu nhập
            $table->date('import_date'); // Ngày nhập hàng
            $table->string('supplier')->nullable(); // Nhà cung cấp (nếu có)
            $table->text('note')->nullable(); // Ghi chú thêm
            $table->decimal('total_cost', 15, 2)->default(0); // Tổng tiền nhập
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_imports');
    }
};
