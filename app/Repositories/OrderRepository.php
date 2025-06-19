<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

readonly class OrderRepository implements OrderRepositoryInterface
{
    private int $ttl;

    public function __construct()
    {
        $this->ttl  = 3600 * 24 * 7;
    }

    public function all(): Collection
    {
        return Cache::remember('orders:all', $this->ttl, function () {
            return Order::paginate(10);
        });
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function allUserOrders(int $userId): Collection
    {
        return Cache::remember('orders:user:orders', $this->ttl, function () use ($userId) {
            return Order::with('products')
                ->where('user_id', $userId)
                ->get();
        });
    }

    /**
     * @param int $userId
     * @param int $orderId
     * @return Order|null
     */
    public function getById(int $userId, int $orderId): ?Order
    {
        return Cache::remember("orders:user:$userId:order:$orderId", $this->ttl, function () use ($userId, $orderId) {
            return Order::with('products')
                ->where('id', $orderId)
                ->where('user_id', $userId)
                ->first();
        });

    }

    /**
     * @param array $orderData
     * @return Order
     */
    public function store(array $orderData): Order
    {
        $order = Order::create($orderData);

        Cache::put("order:$order->id", $order, $this->ttl);

        return $order;
    }
}
