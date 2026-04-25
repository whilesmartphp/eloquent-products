<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\HostWorkspace;
use Tests\TestCase;
use Whilesmart\Products\Models\Product;

class ProductApiTest extends TestCase
{
    #[Test]
    public function post_creates_a_product(): void
    {
        $ws = HostWorkspace::create(['name' => 'Acme']);

        $response = $this->postJson('/api/products', [
            'owner_type' => HostWorkspace::class,
            'owner_id' => $ws->id,
            'name' => 'Consulting hour',
            'type' => 'service',
            'default_price_cents' => 10000,
            'currency' => 'USD',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', 'Consulting hour');
        $this->assertSame(1, Product::count());
    }

    #[Test]
    public function index_filters_by_owner(): void
    {
        $a = HostWorkspace::create(['name' => 'A']);
        $b = HostWorkspace::create(['name' => 'B']);

        $a->products()->create(['name' => 'A1', 'type' => 'product']);
        $a->products()->create(['name' => 'A2', 'type' => 'product']);
        $b->products()->create(['name' => 'B1', 'type' => 'product']);

        $response = $this->getJson('/api/products?owner_type='.urlencode(HostWorkspace::class)."&owner_id={$a->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.meta.total', 2);
    }
}
