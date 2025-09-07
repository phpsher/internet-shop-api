<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\DTO\StoreOrderDTO;
use App\Exceptions\CacheException;
use App\Exceptions\InternalServerErrorException;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * @return LengthAwarePaginator
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
     * @param string $orderId
     * @return Order
     * @throws InternalServerErrorException
     */
    public function getOrder(string $orderId): Order
    {
        return $this->safe(function () use ($orderId) {
            return Cache::remember("orders:$orderId", $this->ttl, function () use ($orderId) {
                return Order::with('products')
                    ->findOrFail($orderId);
            });
        });
    }

    /**
     * @param StoreOrderDTO $DTO
     * @return Order
     * @throws InternalServerErrorException
     */
    public function store(StoreOrderDTO $DTO): Order
    {
        return $this->safe(function () use ($DTO) {
            $order = Order::create($DTO->toArray());

            if (!Cache::put("order:$order->id", $order, $this->ttl)) {
                throw new CacheException('Error storing order in cache');
            }

            Cache::forget('orders:all');

            return $order;
        });
    }
}
