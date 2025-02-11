<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Product;

class ProductApiTest extends TestCase
{
    use DatabaseTransactions;

    // Test untuk menambahkan produk
    public function test_can_create_product()
    {
        $data = [
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 1000,
        ];

        $response = $this->json('POST', '/api/v1/product', $data);

        $response->assertResponseStatus(201);
        $response->seeJson([
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 1000,
        ]);
    }

    // Test untuk mendapatkan produk
    public function test_can_get_products()
    {
        $product = Product::create([
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 1000,
        ]);

        $response = $this->json('GET', '/api/v1/product');

        $response->assertResponseStatus(200);
        $response->seeJson([
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 1000,
        ]);
    }

    // Test untuk menampilkan produk berdasarkan ID
    public function test_can_show_product()
    {
        $product = Product::create([
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 1000,
        ]);

        $response = $this->json('GET', "/api/v1/product/{$product->id}");

        $response->assertResponseStatus(200);
        $response->seeJson([
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 1000,
        ]);
    }

    // Test untuk menghapus produk
    public function test_can_delete_product()
    {
        $product = Product::create([
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 1000,
        ]);

        $response = $this->json('DELETE', "/api/v1/product/{$product->id}");

        $response->assertResponseStatus(200);
        $this->assertNull(Product::find($product->id));
    }

    // Test untuk mengupdate produk
    public function test_can_update_product()
    {
    $product = Product::create([
        'name' => 'Old Product Name',
        'description' => 'Old Product Description',
        'price' => 500,
    ]);

    $data = [
        'name' => 'Updated Product Name',
        'description' => 'Updated Product Description',
        'price' => 1500,
    ];

    $response = $this->json('PUT', "/api/v1/product/{$product->id}", $data);

    $response->assertResponseStatus(200);
    $response->seeJson([
        'name' => 'Updated Product Name',
        'description' => 'Updated Product Description',
        'price' => 1500,
    ]);
    }


    public function test_cannot_create_product_with_missing_name()
    {
        $data = [
            'description' => 'Product Description',
            'price' => 1000,
        ];

        $response = $this->json('POST', '/api/v1/product', $data);

        $response->assertResponseStatus(422); // HTTP Status Unprocessable Entity
        $response->seeJson(['error' => 'The name field is required.']);
    }

    public function test_cannot_create_product_with_invalid_price()
    {
        $data = [
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 'invalid', // Invalid price
        ];

        $response = $this->json('POST', '/api/v1/product', $data);

        $response->assertResponseStatus(422); // HTTP Status Unprocessable Entity
        $response->seeJson(['error' => 'The price must be a number.']);
    }

    public function test_cannot_show_nonexistent_product()
    {
        $response = $this->json('GET', '/api/v1/product/9999'); // Produk dengan ID yang tidak ada

        $response->assertResponseStatus(404); // HTTP Status Not Found
        $response->seeJson(['error' => 'Product not found.']);
    }

    public function test_cannot_delete_nonexistent_product()
    {
        $response = $this->json('DELETE', '/api/v1/product/9999'); // Produk dengan ID yang tidak ada

        $response->assertResponseStatus(404); // HTTP Status Not Found
        $response->seeJson(['error' => 'Product not found.']);
    }

}