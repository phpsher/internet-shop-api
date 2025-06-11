<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\ProductServiceInterface;
use App\Exceptions\InternalServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private readonly ProductServiceInterface $productService
    )
    {
    }

    public function index(): JsonResponse
    {
        try {
            $products = $this->productService->getProducts();
        } catch (InternalServerErrorException $e) {
            return $this->error(
                message: $e->getMessage()
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'Products not found'
            );
        }
        return $this->success(
            data: $products
        );
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->error(
                message: 'Product not found',
            );
        }

        return $this->success(
            data: $product
        );
    }
}
