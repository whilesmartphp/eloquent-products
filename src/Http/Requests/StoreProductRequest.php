<?php

namespace Whilesmart\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Whilesmart\OwnerAccess\Concerns\AuthorizesOwnerRequest;

class StoreProductRequest extends FormRequest
{
    use AuthorizesOwnerRequest;

    public function authorize(): bool
    {
        return $this->authorizeOwnerInRequest();
    }

    public function rules(): array
    {
        return [
            'owner_type' => ['required', 'string'],
            'owner_id' => ['required'],
            'type' => ['nullable', 'in:product,service'],
            'sku' => ['nullable', 'string', 'max:60'],
            'name' => ['required', 'string', 'max:200'],
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
