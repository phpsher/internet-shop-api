<?php

namespace App\Contracts\Repositories;

use App\DTO\StoreOrderDTO;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function all(): LengthAwarePaginator;
    public function getOrder(string $orderId): ?Order;
    public function store(StoreOrderDTO $DTO): Order;
}
