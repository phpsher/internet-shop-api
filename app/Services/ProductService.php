<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }


    public function getProducts(): LengthAwarePaginator
    {
        return $this->productRepository->all();
    }


    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->getById($id);
    }

    /*    public function storeProduct(array $productData): Product
        {
            // TODO...
            $relativePath = $productData['image']->store('public/products');

            $publicUrl = Storage::url($relativePath);

            $productData['image_path'] = $relativePath;

            $productData['image'] = $publicUrl;

            return $this->productRepository->storeProduct($productData);
        }*/

}
