<?php

namespace App\Contracts\Services;

use App\DTO\StoreOrderDTO;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function getOrders(): LengthAwarePaginator;
    public function getOrder(string $orderId): ?Order;
    public function storeOrder(StoreOrderDTO $DTO): Order;

}
