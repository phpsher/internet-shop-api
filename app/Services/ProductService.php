<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Exceptions\InternalServerErrorException;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

readonly class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }


    public function getProducts(): Collection
    {
        return $this->productRepository->all();
    }


    public function getProduct(int $id): Product
    {
        return $this->productRepository->getById($id);
    }
}
