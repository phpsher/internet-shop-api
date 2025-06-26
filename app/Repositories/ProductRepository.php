<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Exceptions\InternalServerErrorException;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;


class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * @return Collection
     * @throws InternalServerErrorException
     */
    public function all(): Collection
    {
        return $this->safe(function () {
            return Cache::remember(
                'products:all',
                $this->ttl,
                function () {
                    return Product::all();
                });
        });
    }

    /**
     * @param int $productId
     * @return Product|null
     * @throws InternalServerErrorException
     */
    public function getById(int $productId): ?Product
    {
        return $this->safe(function () use ($productId) {
            return Cache::remember(
                "product:" . $productId,
                $this->ttl,
                function () use ($productId) {
                    return Product::find($productId);
                });
        });
    }

    /**
     * @param array $productsIds
     * @return Collection
     * @throws InternalServerErrorException
     */
    public function getAllByIds(array $productsIds): Collection
    {
        $cacheKey = 'products:by:ids:' . implode(':', $productsIds);

        return $this->safe(function () use ($cacheKey, $productsIds) {
            return Cache::remember(
                $cacheKey,
                $this->ttl,
                function () use ($productsIds) {
                    return Product::whereIn('id', $productsIds)
                        ->get()
                        ->keyBy('id');
                });
        });
    }

//    public function storeProduct(array $productData): Product
//    {
//         TODO...
//         return Product::create($productData);
//    }
}
