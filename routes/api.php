<?php

use Illuminate\Support\Facades\Route;
use Whilesmart\Products\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class);
