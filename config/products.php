<?php

return [
    'register_routes' => env('PRODUCTS_REGISTER_ROUTES', true),
    'route_prefix' => env('PRODUCTS_ROUTE_PREFIX', 'api'),
    'route_middleware' => ['api', 'auth:sanctum'],
    'table' => env('PRODUCTS_TABLE', 'products'),
];
