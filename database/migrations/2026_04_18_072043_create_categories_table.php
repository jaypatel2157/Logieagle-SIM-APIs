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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete()->comment('Self-reference for nested category tree');
            $table->string('name');
            $table->string('slug')->unique()->comment('Unique slug for lookups and clean URLs');
            $table->boolean('is_active')->default(true)->index()->comment('Indexed because active categories are frequently filtered');
            $table->timestamps();

            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
