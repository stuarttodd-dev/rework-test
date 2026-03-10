<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
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
    $product = Product::factory()->create();
    $warehouse1 = Warehouse::factory()->create();
    $warehouse2 = Warehouse::factory()->create();
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse1->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 100,
        'threshold' => 10,
    ]);
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse2->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 50,
        'threshold' => 5,
    ]);

    $orderPlaced = Order::factory()->placed()->create();
    $product->orderItems()->create([
        'order_uuid' => $orderPlaced->uuid,
        'price' => 10,
        'quantity' => 7,
        'total' => 70,
    ]);

    $service = new ProductService;

    expect($service->physicalQuantity($product))->toBe(157);
});

test('total threshold is sum of all thresholds across warehouse locations', function (): void {
    $product = Product::factory()->create();
    $warehouse1 = Warehouse::factory()->create();
    $warehouse2 = Warehouse::factory()->create();
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse1->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 100,
        'threshold' => 20,
    ]);
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse2->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 30,
        'threshold' => 15,
    ]);

    $service = new ProductService;

    expect($service->totalThreshold($product))->toBe(35);
});

test('immediate despatch is sum of quantity minus total threshold', function (): void {
    $product = Product::factory()->create();
    $warehouse1 = Warehouse::factory()->create();
    $warehouse2 = Warehouse::factory()->create();
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse1->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 100,
        'threshold' => 10,
    ]);
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse2->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 20,
        'threshold' => 25,
    ]);

    $service = new ProductService;

    expect($service->immediateDespatch($product))->toBe(85);
});
