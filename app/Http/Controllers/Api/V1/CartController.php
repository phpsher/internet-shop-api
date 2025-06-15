<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\CartServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyProductFromCartRequest;
use App\Http\Requests\StoreProductToCartRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use ResponseTrait;

    private readonly string $cartKey;

    public function __construct(
        protected CartServiceInterface $cartService
    )
    {
        $this->cartKey = 'cart: ' . Auth::id();
    }


    public function index(): JsonResponse
    {
        $cart = $this->cartService->getCart($this->cartKey);


        return $this->success(
            data: $cart
        );
    }

    public function store(StoreProductToCartRequest $request): JsonResponse
    {
        $products = $this->cartService->addProductToCart([
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
        ], $this->cartKey);


        return $this->success(
            message: 'Successfully added to cart',
            data: [
                'products' => $products,
            ]
        );
    }


    public function destroy(DestroyProductFromCartRequest $request): JsonResponse
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $this->cartService->deleteProductFromCart([
            'product_id' => $productId,
            'quantity' => $quantity
        ], $this->cartKey);

        return $this->success(
            message: 'Product removed from cart',
        );
    }
}
