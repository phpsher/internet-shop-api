<?php

namespace App\Contracts\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function all(): LengthAwarePaginator;
    public function allUserOrders(int $userId): Collection;

    public function getById(int $userId, int $orderId): ?Order;

    public function store(array $orderData): Order;
}
