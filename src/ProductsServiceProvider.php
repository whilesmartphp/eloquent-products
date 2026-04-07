<?php

namespace Whilesmart\Products;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProductsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/products.php', 'products');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/products.php' => config_path('products.php'),
        ], 'products-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'products-migrations');

        if (config('products.register_routes', true)) {
            Route::middleware(config('products.route_middleware', ['api', 'auth:sanctum']))
                ->prefix(config('products.route_prefix', 'api'))
                ->group(__DIR__.'/../routes/api.php');
        }
    }
}
