<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

readonly class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private int $ttl = 3600 * 24 * 7,
    )
    {
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Cache::remember('products:all', $this->ttl, function () {
            return Product::all();
        });
    }

    /**
     * @param int $productId
     * @return Product|null
     */
    public function getById(int $productId): ?Product
    {
        return Cache::remember("posts:$productId", $this->ttl, function () use ($productId) {
            return Product::find($productId);
        });
    }

    /**
     * @param array $productsIds
     * @return Collection
     */
    public function getAllByIds(array $productsIds): Collection
    {
        $cacheKey = 'products_by_ids_' . implode('_', $productsIds);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($productsIds) {
            return Product::whereIn('id', $productsIds)->get()->keyBy('id');
        });
    }
}
