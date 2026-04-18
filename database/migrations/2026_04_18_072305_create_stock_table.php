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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->comment('Stock always belongs to a product');
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete()->comment('Stock always belongs to a warehouse');
            $table->unsignedBigInteger('quantity')->default(0)->comment('Current on-hand stock');
            $table->unsignedBigInteger('reserved_quantity')->default(0)->comment('Reserved stock not yet available for sale/use');
            $table->timestamps();
            $table->unique(['product_id', 'warehouse_id'], 'stock_product_warehouse_unique');
            $table->index(['warehouse_id', 'product_id'], 'stock_warehouse_product_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
