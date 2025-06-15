<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\CartServiceInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ResponseTrait;

    private readonly string $cartKey;

    public function __construct(
        private readonly CartServiceInterface  $cartService,
        private readonly OrderServiceInterface $orderService,
    )
    {
        $this->cartKey = 'cart: ' . Auth::id();
    }

    public function index(): JsonResponse
    {
        $orders = $this->orderService->getOrders(Auth::id());

        return $this->success(
            data: $orders
        );
    }

    public function show(int $orderId): JsonResponse
    {
        $order = $this->orderService->getOrder(Auth::id(), $orderId);

        return $this->success(
            data: $order
        );
    }

    // Метод для создания заказа
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->storeOrder(Auth::id(), $request->input('products'));

        return $this->success(
            message: 'Order created',
            data: [
                'order' => $order
            ]
        );
    }
}
