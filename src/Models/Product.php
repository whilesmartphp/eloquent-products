<?php

namespace Whilesmart\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whilesmart\Invoices\Contracts\Invoiceable;
use Whilesmart\Invoices\Traits\IsInvoiceable;
use Whilesmart\Products\Database\Factories\ProductFactory;
use Whilesmart\Products\Enums\ProductType;

class Product extends Model implements Invoiceable
{
    use HasFactory, IsInvoiceable, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ProductType::class,
        'is_active' => 'boolean',
        'metadata' => 'array',
        'default_tax_rate' => 'decimal:4',
    ];

    public function getTable(): string
    {
        return config('products.table', 'products');
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
