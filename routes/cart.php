<?php

use App\Http\Controllers\Api\V1\CartController;

Route::middleware('auth:sanctum')
    ->prefix('cart')
    ->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::delete('/', [CartController::class, 'destroy']);
    });
