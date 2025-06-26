<?php

use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')
    ->group(function () {
    // Product routes
    Route::prefix('/products')
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/{productId}', [ProductController::class, 'show']);
        });

        // Admin routes
        Route::prefix('admin')
            ->middleware(['auth:sanctum', AdminMiddleware::class])
            ->group(function () {
                Route::get('/dashboard', [AdminController::class, 'dashboard']);
                Route::get('/orders/{userId}', [AdminController::class, 'allUserOrders']);
                Route::get('/statistics', [AdminController::class, 'statistics']);
                // TODO... Route::put('/products/{productId}', [ProductController::class, 'update']);
            });


    // Auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Order routes
    Route::prefix('orders')
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('/{id}', [OrderController::class, 'show']);
            Route::post('/', [OrderController::class, 'store']);
        });

    // Test routes
    Route::get('/protected-route', function () {
        return response()->json(['message' => 'Protected route accessed']);
    })->middleware('auth:sanctum');

    Route::get('/protected-route-admin', function () {
        return response()->json(['message' => 'Protected route accessed']);
    })->middleware(AdminMiddleware::class);
});



