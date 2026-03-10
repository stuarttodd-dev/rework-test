<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;

class WarehouseStockSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $warehouses = Warehouse::all();

        if ($products->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        foreach ($products as $product) {
            foreach ($warehouses as $warehouse) {
                WarehouseStock::factory()->create([
                    'warehouse_uuid' => $warehouse->uuid,
                    'product_uuid' => $product->uuid,
                ]);
            }
        }
    }
}
