<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(): Response
    {
        $products = Product::query()
            ->with(['warehouseStock.warehouse:uuid,name'])
            ->orderBy('title')
            ->get()
            ->map(function (Product $product): array {
                $breakdown = $product->warehouseStock
                    ->map(fn ($ws) => [
                        'warehouse_name' => $ws->warehouse->name,
                        'quantity' => $ws->quantity,
                    ])
                    ->values()
                    ->all();

                return [
                    'uuid' => $product->uuid,
                    'title' => $product->title,
                    'total_quantity' => $product->warehouseStock->sum('quantity'),
                    'allocated_to_orders' => $this->productService->allocatedToOrders($product),
                    'physical_quantity' => $this->productService->physicalQuantity($product),
                    'total_threshold' => $this->productService->totalThreshold($product),
                    'immediate_despatch' => $this->productService->immediateDespatch($product),
                    'warehouse_breakdown' => $breakdown,
                ];
            });

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }
}
