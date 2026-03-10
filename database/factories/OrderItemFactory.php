<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $price = fake()->randomFloat(2, 2, 200);
        $total = round($price * $quantity, 2);

        return [
            'order_uuid' => Order::factory(),
            'product_uuid' => Product::factory(),
            'price' => $price,
            'quantity' => $quantity,
            'total' => $total,
        ];
    }
}
