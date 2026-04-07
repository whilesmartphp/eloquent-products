<?php

namespace Whilesmart\Products\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Whilesmart\Products\Enums\ProductType;
use Whilesmart\Products\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'type' => ProductType::Product,
            'sku' => strtoupper($this->faker->bothify('SKU-####??')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'default_unit' => 'ea',
            'default_price_cents' => $this->faker->numberBetween(500, 50000),
            'currency' => 'USD',
            'is_active' => true,
        ];
    }

    public function service(): static
    {
        return $this->state(fn () => [
            'type' => ProductType::Service,
            'default_unit' => 'hour',
            'default_price_cents' => $this->faker->numberBetween(2500, 25000),
        ]);
    }
}
