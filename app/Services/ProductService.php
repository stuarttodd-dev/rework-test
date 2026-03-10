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

    public function physicalQuantity(Product $product): int
    {
        $warehouseQuantity = (int) $product->warehouseStock()->sum('quantity');

        return $warehouseQuantity + $this->allocatedToOrders($product);
    }

    public function totalThreshold(Product $product): int
    {
        return (int) $product->warehouseStock()->sum('threshold');
    }

    public function immediateDespatch(Product $product): int
    {
        $totalQuantity = (int) $product->warehouseStock()->sum('quantity');
        $totalThreshold = $this->totalThreshold($product);

        return max(0, $totalQuantity - $totalThreshold);
    }
}
