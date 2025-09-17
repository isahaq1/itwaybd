<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['sale_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};


