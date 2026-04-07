<?php

namespace Whilesmart\Products\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'type' => $this->type?->value,
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'default_unit' => $this->default_unit,
            'default_price_cents' => $this->default_price_cents,
            'default_cost_cents' => $this->default_cost_cents,
            'default_tax_rate' => $this->default_tax_rate ? (float) $this->default_tax_rate : null,
            'currency' => $this->currency,
            'category' => $this->category,
            'is_active' => $this->is_active,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
        ];
    }
}
