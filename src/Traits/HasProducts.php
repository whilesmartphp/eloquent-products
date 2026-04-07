<?php

namespace Whilesmart\Products\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Whilesmart\Products\Models\Product;

trait HasProducts
{
    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'owner');
    }
}
