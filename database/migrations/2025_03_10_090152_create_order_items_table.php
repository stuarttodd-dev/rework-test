<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('order_uuid')->constrained('orders', 'uuid')->cascadeOnDelete();
            $table->foreignUuid('product_uuid')->constrained('products', 'uuid')->cascadeOnDelete();
            $table->decimal('price');
            $table->unsignedInteger('quantity');
            $table->decimal('total');
            $table->timestamps();

            $table->index(['order_uuid', 'product_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
