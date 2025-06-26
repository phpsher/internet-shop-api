<?php

namespace App\Contracts\Services;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function getOrders(): LengthAwarePaginator;
    public function getUserOrders(int $userId): Collection;
    public function getOrder(int $userId, int $orderId): Order;
    public function storeOrder(int $userId, array $productsData): Order;

}
