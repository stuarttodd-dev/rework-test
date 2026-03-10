<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;

test('store order fails when quantity exceeds maximum available in a single warehouse', function (): void {
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 10,
        'threshold' => 2,
    ]);

    $response = $this->post(route('orders.store'), [
        'product_uuid' => $product->uuid,
        'quantity' => 11,
    ]);

    $response->assertSessionHasErrors('quantity');
    $this->assertStringContainsString('cannot exceed 10', session('errors')->first('quantity'));
});

test('store order fails when product has no stock', function (): void {
    $product = Product::factory()->create();

    $response = $this->post(route('orders.store'), [
        'product_uuid' => $product->uuid,
        'quantity' => 1,
    ]);

    $response->assertSessionHasErrors('quantity');
    $this->assertStringContainsString('no stock', session('errors')->first('quantity'));
});

test('store order succeeds when quantity equals max warehouse stock', function (): void {
    $product = Product::factory()->create(['price' => 10]);
    $warehouse = Warehouse::factory()->create();
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 5,
        'threshold' => 0,
    ]);

    $response = $this->post(route('orders.store'), [
        'product_uuid' => $product->uuid,
        'quantity' => 5,
    ]);

    $response->assertRedirect(route('orders.create'));
    $response->assertSessionHas('success', 'Order placed successfully.');
});

test('store creates order and order item with correct totals', function (): void {
    $product = Product::factory()->create(['price' => 25.50]);
    $warehouse = Warehouse::factory()->create();
    WarehouseStock::create([
        'warehouse_uuid' => $warehouse->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 10,
        'threshold' => 0,
    ]);

    $this->post(route('orders.store'), [
        'product_uuid' => $product->uuid,
        'quantity' => 3,
    ]);

    $order = Order::latest()->first();
    expect($order)->not->toBeNull()
        ->and($order->order_status)->toBe(OrderStatus::Placed)
        ->and((float) $order->total)->toBe(76.5);

    $item = OrderItem::where('order_uuid', $order->uuid)->first();
    expect($item)->not->toBeNull()
        ->and($item->product_uuid)->toBe($product->uuid)
        ->and($item->quantity)->toBe(3)
        ->and((float) $item->price)->toBe(25.5)
        ->and((float) $item->total)->toBe(76.5);
});

test('store deducts quantity from warehouse with most stock', function (): void {
    $product = Product::factory()->create();
    $warehouseA = Warehouse::factory()->create();
    $warehouseB = Warehouse::factory()->create();
    WarehouseStock::create([
        'warehouse_uuid' => $warehouseA->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 10,
        'threshold' => 0,
    ]);
    WarehouseStock::create([
        'warehouse_uuid' => $warehouseB->uuid,
        'product_uuid' => $product->uuid,
        'quantity' => 5,
        'threshold' => 0,
    ]);

    $this->post(route('orders.store'), [
        'product_uuid' => $product->uuid,
        'quantity' => 3,
    ]);

    $stockA = WarehouseStock::where('warehouse_uuid', $warehouseA->uuid)
        ->where('product_uuid', $product->uuid)
        ->first();
    $stockB = WarehouseStock::where('warehouse_uuid', $warehouseB->uuid)
        ->where('product_uuid', $product->uuid)
        ->first();

    expect($stockA->quantity)->toBe(7)
        ->and($stockB->quantity)->toBe(5);
});
