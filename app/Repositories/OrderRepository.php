<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

readonly class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @param int $userId
     * @return Collection
     */
    public function all(int $userId): Collection
    {
        return Order::with('products')->get();
    }

    /**
     * @param int $userId
     * @param int $orderId
     * @return Order|null
     */
    public function getById(int $userId, int $orderId): ?Order
    {
        return Order::with('products')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * @param array $orderData
     * @return Order
     */
    public function store(array $orderData): Order
    {
        return Order::create($orderData);
    }
}
