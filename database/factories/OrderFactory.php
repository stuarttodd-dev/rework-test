<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_status' => fake()->randomElement(OrderStatus::cases()),
            'total' => fake()->randomFloat(2, 10, 2000),
        ];
    }

    public function placed(): static
    {
        return $this->state(fn (array $attributes) => ['order_status' => OrderStatus::Placed]);
    }

    public function dispatched(): static
    {
        return $this->state(fn (array $attributes) => ['order_status' => OrderStatus::Dispatched]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => ['order_status' => OrderStatus::Cancelled]);
    }
}
