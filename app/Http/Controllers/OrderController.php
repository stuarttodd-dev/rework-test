<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\WarehouseStock;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function create(): Response
    {
        $products = Product::query()
            ->orderBy('title')
            ->get(['uuid', 'title', 'price'])
            ->map(fn ($p) => [
                'uuid' => $p->uuid,
                'title' => $p->title,
                'price' => (float) $p->price,
            ]);

        return Inertia::render('Orders/Create', [
            'products' => $products,
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->input('product_uuid'));
        $quantity = (int) $request->input('quantity');

        $orderTotal = (float) $product->price * $quantity;
        $order = Order::create([
            'order_status' => OrderStatus::Placed,
            'total' => $orderTotal,
        ]);

        OrderItem::create([
            'order_uuid' => $order->uuid,
            'product_uuid' => $product->uuid,
            'price' => $product->price,
            'quantity' => $quantity,
            'total' => $orderTotal,
        ]);

        $warehouseStock = $product->warehouseStock()
            ->orderByDesc('quantity')
            ->first();

        WarehouseStock::where('warehouse_uuid', $warehouseStock->warehouse_uuid)
            ->where('product_uuid', $warehouseStock->product_uuid)
            ->decrement('quantity', $quantity);

        return redirect()->route('orders.create')->with('success', 'Order placed successfully.');
    }
}
