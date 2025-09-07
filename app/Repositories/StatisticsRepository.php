<?php

namespace App\Repositories;

use App\Contracts\Repositories\StatisticsRepositoryInterface;
use App\Exceptions\InternalServerErrorException;
use App\Models\Order;
use App\Models\Product;
use Cache;

class StatisticsRepository extends BaseRepository implements StatisticsRepositoryInterface
{
    /**
     * @return array
     * @throws InternalServerErrorException
     */
    public function getOrderStatistics(): array
    {
        return $this->safe(function () {
            $orderStatistics = Cache::remember('orders:statistics', $this->ttl, function () {
                return Order::selectRaw(
                    "COUNT(*) as total,
     SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
     SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
     SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled"
                )->first();
            });

            return [
                'total' => (int)$orderStatistics->total,
                'pending' => (int)$orderStatistics->pending,
                'completed' => (int)$orderStatistics->completed,
                'cancelled' => (int)$orderStatistics->cancelled,
            ];
        });
    }


    /**
     * @return array
     * @throws InternalServerErrorException
     */
    public function getProductStatistics(): array
    {
        return $this->safe(function () {
            $totalProducts = Cache::remember('products:total', $this->ttl, function () {
                return Product::count();
            });

            return [
                'total' => (int)$totalProducts,
            ];
        });
    }
}
