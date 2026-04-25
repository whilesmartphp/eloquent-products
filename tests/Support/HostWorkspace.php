<?php

namespace Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Whilesmart\Products\Models\Product;

class HostWorkspace extends Model
{
    protected $table = 'workspaces';

    protected $guarded = [];

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'owner');
    }
}
