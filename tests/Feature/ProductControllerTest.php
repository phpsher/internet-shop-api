<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'price',
                    'image',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }


    public function test_can_get_single_product()
    {
        $product = Product::factory()->count(1)->create();

        $response = $this->getJson('/api/v1/products/' . $product->first()->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'price',
                'image',
                'created_at',
                'updated_at',
            ]
        ]);

        $response->assertJson([
            'data' => $product->first()->toArray()
        ]);
    }
}
