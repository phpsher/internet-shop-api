<?php

use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    Route::middleware('auth:sanctum')->prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::delete('/', [CartController::class, 'destroy']);
    });

    Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{orderId}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
    });

    Route::middleware(['auth:sanctum', AdminMiddleware::class])->prefix('admin')->group(function () {
        Route::get('/products', [AdminController::class, 'allProducts']);
        Route::get('/products/{productId}', [AdminController::class, 'showProduct']);
        Route::get('/orders', [AdminController::class, 'allOrders']);
        Route::get('/users/{user_id}/orders', [AdminController::class, 'allUserOrders']);
        Route::get('/users/{user_id}/orders/{orderId}', [AdminController::class, 'showOrder']);
    });
});
