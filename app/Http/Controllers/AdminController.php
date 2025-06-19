<?php

namespace App\Http\Controllers;

use App\Contracts\Services\OrderServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Http\Requests\ShowOrderRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly OrderServiceInterface   $orderService,
    )
    {
    }

    public function allProducts(): JsonResponse
    {
        $products = $this->productService->getProducts();

        return $this->success(
            data: $products
        );
    }

    public function showProduct(int $productId): JsonResponse
    {
        $product = $this->productService->getProduct($productId);

        return $this->success(
            data: $product
        );
    }

    public function allOrders(): JsonResponse
    {
        $orders = $this->orderService->getOrders();

        return $this->success(
            data: $orders
        );
    }

    public function allUserOrders(ShowOrderRequest $request): JsonResponse
    {
        $orders = $this->orderService->getUserOrders($request->user_id);

        return $this->success(
            data: $orders
        );
    }

    public function showOrder(ShowOrderRequest $request, int $orderId): JsonResponse
    {
        $order = $this->orderService->getOrder($request->user_id, $orderId);

        return $this->success(
            data: $order
        );
    }

}
