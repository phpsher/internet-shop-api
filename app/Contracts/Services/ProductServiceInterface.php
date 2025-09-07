<?php

namespace App\Contracts\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    public function getProducts(): LengthAwarePaginator;

    public function getProduct(int $id): ?Product;

    // TODO... public function storeProduct(array $productData): Product;
}
