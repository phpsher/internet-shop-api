<?php

namespace App\Contracts\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function all(int $userId): Collection;

    public function getById(int $userId, int $orderId): ?Order;

    public function store(array $orderData): Order;
}
