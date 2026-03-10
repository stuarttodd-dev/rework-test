<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_uuid' => ['required', 'string', 'exists:products,uuid'],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function (string $attribute, int $value, \Closure $fail): void {
                    unset($attribute);
                    $product = Product::find($this->input('product_uuid'));
                    if (! $product) {
                        return;
                    }
                    $maxStock = (int) $product->warehouseStock()->max('quantity');
                    if ($maxStock === 0) {
                        $fail('This product has no stock in any warehouse.');
                        return;
                    }
                    if ($value > $maxStock) {
                        $fail("Quantity cannot exceed {$maxStock} (maximum available in a single warehouse).");
                    }
                },
            ],
        ];
    }
}
