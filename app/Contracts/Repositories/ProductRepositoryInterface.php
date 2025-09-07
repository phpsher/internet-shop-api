<?php

namespace App\Contracts\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function all(): LengthAwarePaginator;

    public function getById(int $productId): ?Product;

    public function getAllByIds(array $productsIds): Collection;

    // TODO... public function storeProduct(array $productData): ?Product;
}
