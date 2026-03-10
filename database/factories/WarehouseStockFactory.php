<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseStockFactory extends Factory
{
    protected $model = WarehouseStock::class;

    public function definition(): array
    {
        return [
            'warehouse_uuid' => Warehouse::factory(),
            'product_uuid' => Product::factory(),
            'quantity' => fake()->numberBetween(0, 1000),
            'threshold' => fake()->numberBetween(0, 50),
        ];
    }
}
