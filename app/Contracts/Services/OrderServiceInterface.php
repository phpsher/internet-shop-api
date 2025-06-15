<?php

namespace App\Contracts\Services;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface
{
    public function getOrders(int $userId): Collection;


    public function getOrder(int $userId, int $orderId): Order;


    public function storeOrder(int $userId, array $productsData): Order;

}
