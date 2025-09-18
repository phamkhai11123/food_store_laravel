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
        Schema::table('inventory_transactions', function (Blueprint $table) {
        $table->enum('type', ['import', 'export', 'loss'])->after('ingredient_id');
        $table->string('unit', 10)->after('quantity_base');
        $table->decimal('unit_cost', 18, 2)->nullable()->after('unit');
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('ref_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
