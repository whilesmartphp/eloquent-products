<?php

namespace Whilesmart\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', 'in:product,service'],
            'sku' => ['nullable', 'string', 'max:60'],
            'name' => ['sometimes', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'default_unit' => ['nullable', 'string', 'max:30'],
            'default_price_cents' => ['nullable', 'integer', 'min:0'],
            'default_cost_cents' => ['nullable', 'integer', 'min:0'],
            'default_tax_rate' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
