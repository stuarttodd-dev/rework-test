<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $prefixes = [
            'Premium', 'Pro', 'Classic', 'Deluxe', 'Essential', 'Eco', 'Smart',
            'Stainless Steel', 'Wireless', 'Portable', 'Compact', 'Heavy Duty',
        ];

        $products = [
            'Wireless Earbuds', 'Desk Lamp', 'Kitchen Knife Set', 'Water Bottle',
            'Bluetooth Speaker', 'Running Shoes', 'Backpack', 'Coffee Maker',
            'Phone Stand', 'Desk Organiser', 'Yoga Mat', 'LED Torch',
            'Insulated Mug', 'Laptop Sleeve', 'Screwdriver Set', 'Gardening Gloves',
        ];

        $title = fake()->randomElement($prefixes).' '.fake()->randomElement($products);

        return [
            'title' => $title,
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5, 500),
        ];
    }
}
