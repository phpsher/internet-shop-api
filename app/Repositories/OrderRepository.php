<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Exceptions\CacheException;
use App\Exceptions\InternalServerErrorException;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * @throws InternalServerErrorException
     */
    public function all(): LengthAwarePaginator
    {
        return $this->safe(function () {
            return Cache::remember('orders:all', $this->ttl, function () {
                return Order::paginate(10);
            });
        });

    }

    /**
     * @param int $userId
     * @return Collection
     * @throws InternalServerErrorException
     */
    public function allUserOrders(int $userId): Collection
    {
        return $this->safe(function () use ($userId) {
            return Cache::remember("orders:user:orders:$userId", $this->ttl, function () use ($userId) {
                return Order::with('products')
                    ->where('user_id', $userId)
                    ->get();
            });
        });
    }

    /**
     * @param int $userId
     * @param int $orderId
     * @return Order|null
     * @throws InternalServerErrorException
     */
    public function getById(int $userId, int $orderId): ?Order
    {
        return $this->safe(function () use ($userId, $orderId) {
            return Cache::remember("orders:user:$userId:order:$orderId", $this->ttl, function () use ($userId, $orderId) {
                return Order::with('products')
                    ->where('id', $orderId)
                    ->where('user_id', $userId)
                    ->first();
            });
        });
    }

    /**
     * @param array $orderData
     * @return Order
     * @throws InternalServerErrorException
     */
    public function store(array $orderData): Order
    {
        return $this->safe(function () use ($orderData) {
            $order = Order::create($orderData);


            if(!Cache::put("order:$order->id", $order, $this->ttl)) {
                throw new CacheException('Error storing order in cache');
            }

            return $order;
        });
    }
}
