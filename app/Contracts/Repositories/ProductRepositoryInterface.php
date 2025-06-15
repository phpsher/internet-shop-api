<?php

namespace App\Contracts\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function all(): Collection;

    public function getById(int $productId): ?Product;

    public function getAllByIds(array $productsIds): Collection;
}
