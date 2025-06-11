<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Exceptions\InternalServerErrorException;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

readonly class ProductRepository implements ProductRepositoryInterface
{


    /**
     * @throws InternalServerErrorException
     */
    public function all(): Collection
    {
        try {
            return Product::all();
        } catch (QueryException $e) {
            throw new InternalServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws InternalServerErrorException
     */
    public function find(int $id): ?Product
    {
        try {
            return Product::find($id);
        } catch (QueryException $e) {
            throw new InternalServerErrorException($e->getMessage());
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e->getMessage());
        }
    }
}
