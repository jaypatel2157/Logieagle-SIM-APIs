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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete()->comment('Products category');
            $table->string('name')->index()->comment('Indexed because products can be sorted/searched by name');
            $table->string('sku')->unique()->comment('Unique SKU');
            $table->text('description')->nullable();
            $table->decimal('base_price', 12, 2)->index()->comment('Indexed for min/max price filtering and sorting');
            $table->boolean('is_active')->default(true)->index()->comment('Indexed because inactive products will commonly be excluded');
            $table->timestamps();

            $table->index('category_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
