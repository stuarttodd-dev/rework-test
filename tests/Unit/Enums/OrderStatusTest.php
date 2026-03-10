<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('order status enum has expected cases', function (): void {
    expect(OrderStatus::cases())->toHaveCount(3)
        ->and(OrderStatus::Placed)->toBeInstanceOf(OrderStatus::class)
        ->and(OrderStatus::Dispatched)->toBeInstanceOf(OrderStatus::class)
        ->and(OrderStatus::Cancelled)->toBeInstanceOf(OrderStatus::class);
});

test('get default returns placed', function (): void {
    expect(OrderStatus::getDefault())->toBe(OrderStatus::Placed);
});

test('order status enum has correct string values', function (): void {
    expect(OrderStatus::Placed->value)->toBe('placed')
        ->and(OrderStatus::Dispatched->value)->toBe('dispatched')
        ->and(OrderStatus::Cancelled->value)->toBe('cancelled');
});

test('order model casts order_status to OrderStatus enum', function (): void {
    $order = Order::factory()->placed()->create();

    expect($order->order_status)->toBe(OrderStatus::Placed)
        ->and($order->getRawOriginal('order_status'))->toBe('placed');
});

test('order model persists enum value as string', function (): void {
    $order = Order::factory()->create(['order_status' => OrderStatus::Cancelled]);

    expect($order->order_status)->toBe(OrderStatus::Cancelled);
    $order->refresh();
    expect($order->order_status)->toBe(OrderStatus::Cancelled);
});
