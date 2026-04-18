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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->comment('Movement belongs to a product');
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete()->comment('Movement belongs to a warehouse');
            $table->string('movement_type')->index()->comment('Movement type is filtered and aggregated in reports');
            $table->unsignedBigInteger('quantity')->comment('Always store positive quantity; meaning comes from movement_type');
            $table->string('reference_id')->nullable()->index()->comment('External/internal reference for tracing source action');
            $table->string('reference_type')->nullable()->index()->comment('Reference type for polymorphic-like traceability');
            $table->text('note')->nullable();
            $table->timestamp('moved_at')->index()->comment('Indexed because movement history supports date range filters');
            $table->timestamps();

            $table->index(['product_id', 'warehouse_id'], 'movements_product_warehouse_index');
            $table->index(['product_id', 'moved_at'], 'movements_product_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
