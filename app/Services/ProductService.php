<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Exceptions\InternalServerErrorException;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    /**
     * @throws InternalServerErrorException
     */
    public function getProducts(): Collection
    {
        try {
            return $this->productRepository->all();
        } catch (InternalServerErrorException $e) {
            throw new InternalServerErrorException($e->getMessage());
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e->getMessage());
        }
    }

    /**
     * @throws InternalServerErrorException
     */
    public function getProduct(int $id): Product
    {
        try {
            return $this->productRepository->find($id);
        } catch (InternalServerErrorException $e) {
            throw new InternalServerErrorException($e->getMessage());
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e->getMessage());
        }
    }
}
