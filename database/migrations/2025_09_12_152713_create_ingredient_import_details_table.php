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
        Schema::create('ingredient_import_details', function (Blueprint $table) {
            $table->id();

            // Liên kết tới bảng ingredient_imports
            $table->unsignedBigInteger('ingredient_import_id');
            $table->foreign('ingredient_import_id')
                ->references('id')->on('ingredient_imports')
                ->onDelete('cascade');

            // Liên kết tới bảng ingredients
            $table->unsignedBigInteger('ingredient_id');
            $table->foreign('ingredient_id')
                ->references('id')->on('ingredients')
                ->onDelete('cascade');

            $table->integer('quantity'); // Số lượng nhập
            $table->decimal('unit_price', 12, 4); // Giá nhập từng đơn vị
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_import_details');
    }
};
