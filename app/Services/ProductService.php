<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
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
        try {
            return $this->productRepository->all();
        } catch (\Exception $e) {
            return new Collection();
        }
    }


    public function getProduct(int $id): ?Product
    {
        try {
            return $this->productRepository->getById($id);
        } catch (\Exception $e) {
            return null;
        }
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
