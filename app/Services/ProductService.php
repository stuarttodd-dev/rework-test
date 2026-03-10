<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Product;

class ProductService
{
    public function allocatedToOrders(Product $product): int
    {
        return (int) $product->orderItems()
            ->whereHas('order', fn ($q) => $q->where('order_status', '!=', OrderStatus::Dispatched))
            ->sum('quantity');
    }
}
