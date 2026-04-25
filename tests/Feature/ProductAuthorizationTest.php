<?php

namespace Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\HostWorkspace;
use Tests\TestCase;
use Whilesmart\OwnerAccess\Contracts\OwnerAuthorizer;

class ProductAuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(OwnerAuthorizer::class, new class implements OwnerAuthorizer
        {
            public function authorize(?Authenticatable $user, string $ownerType, mixed $ownerId): bool
            {
                return false;
            }

            public function scope(Builder $query, ?Authenticatable $user, string $ownerTypeColumn = 'owner_type', string $ownerIdColumn = 'owner_id'): Builder
            {
                return $query->whereRaw('0 = 1');
            }
        });
    }

    #[Test]
    public function store_returns_403_when_authorizer_denies(): void
    {
        $ws = HostWorkspace::create(['name' => 'Acme']);

        $this->postJson('/api/products', [
            'owner_type' => HostWorkspace::class,
            'owner_id' => $ws->id,
            'name' => 'Denied product',
        ])->assertForbidden();
    }

    #[Test]
    public function show_returns_403_when_authorizer_denies(): void
    {
        $ws = HostWorkspace::create(['name' => 'Acme']);
        $product = $ws->products()->create(['name' => 'P1', 'type' => 'product']);

        $this->getJson("/api/products/{$product->id}")->assertForbidden();
    }

    #[Test]
    public function update_returns_403_when_authorizer_denies(): void
    {
        $ws = HostWorkspace::create(['name' => 'Acme']);
        $product = $ws->products()->create(['name' => 'P1', 'type' => 'product']);

        $this->putJson("/api/products/{$product->id}", ['name' => 'Renamed'])
            ->assertForbidden();

        $this->assertSame('P1', $product->fresh()->name);
    }

    #[Test]
    public function destroy_returns_403_when_authorizer_denies(): void
    {
        $ws = HostWorkspace::create(['name' => 'Acme']);
        $product = $ws->products()->create(['name' => 'P1', 'type' => 'product']);

        $this->deleteJson("/api/products/{$product->id}")->assertForbidden();
        $this->assertNotNull($product->fresh());
    }

    #[Test]
    public function index_applies_scope_from_authorizer(): void
    {
        $ws = HostWorkspace::create(['name' => 'Acme']);
        $ws->products()->create(['name' => 'P1', 'type' => 'product']);

        $response = $this->getJson('/api/products')->assertOk();

        $this->assertSame(0, $response->json('data.meta.total'));
    }
}
