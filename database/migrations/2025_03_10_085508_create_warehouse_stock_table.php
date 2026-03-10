<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_stock', function (Blueprint $table) {
            $table->foreignUuid('warehouse_uuid')->constrained('warehouses', 'uuid')->cascadeOnDelete();
            $table->foreignUuid('product_uuid')->constrained('products', 'uuid')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('threshold')->default(0);
            $table->timestamps();

            $table->primary(['warehouse_uuid', 'product_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock');
    }
};
