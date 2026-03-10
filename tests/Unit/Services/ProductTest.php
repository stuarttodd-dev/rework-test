<?php

use App\Models\Order;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('allocated to orders returns sum of order item quantity where order is not dispatched', function (): void {
    $product = Product::factory()->create();
    $orderPlaced = Order::factory()->placed()->create();
    $orderDispatched = Order::factory()->dispatched()->create();

    $product->orderItems()->create([
        'order_uuid' => $orderPlaced->uuid,
        'price' => 10,
        'quantity' => 3,
        'total' => 30,
    ]);
    $product->orderItems()->create([
        'order_uuid' => $orderPlaced->uuid,
        'price' => 10,
        'quantity' => 2,
        'total' => 20,
    ]);
    $product->orderItems()->create([
        'order_uuid' => $orderDispatched->uuid,
        'price' => 10,
        'quantity' => 5,
        'total' => 50,
    ]);

    $service = new ProductService;

    expect($service->allocatedToOrders($product))->toBe(5);
});

test('physical quantity is sum of warehouse stock quantity plus allocated to orders', function (): void {
})->skip();

test('total threshold is sum of all thresholds across warehouse locations', function (): void {
})->skip();

test('immediate despatch is sum of quantity minus total threshold', function (): void {
})->skip();
