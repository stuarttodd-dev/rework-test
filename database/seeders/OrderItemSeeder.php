<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        if ($orders->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($orders as $order) {
            $itemsCount = rand(1, 4);
            $orderProducts = $products->random(min($itemsCount, $products->count()));

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 5);
                $price = (float) $product->price;
                $total = round($price * $quantity, 2);

                OrderItem::factory()->create([
                    'order_uuid' => $order->uuid,
                    'product_uuid' => $product->uuid,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $total,
                ]);
            }
        }
    }
}
