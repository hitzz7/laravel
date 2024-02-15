<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Item;
use App\Models\Image;

class ProductControllerTest extends TestCase
{
    

    public function testIndex()
    {
        $response = $this->get('/api/products');
        $response->assertStatus(200);
    }

    public function testShow()
    {
        // Manually create a product with items
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
        ]);

        // Add items to the product
        $item1 = Item::create([
            'product_id' => $product->id,
            'size' => 'XL',
            'color' => 'Red',
            'status' => 'In Stock',
            'sku' => 'SKU123',
            'price' => 19.99,
        ]);

        $item2 = Item::create([
            'product_id' => $product->id,
            'size' => 'M',
            'color' => 'Blue',
            'status' => 'Out of Stock',
            'sku' => 'SKU456',
            'price' => 29.99,
        ]);

        // Send a GET request to the API endpoint
        $response = $this->get('/api/products/' . $product->id);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Optionally, you can assert specific properties of the response JSON
        $response->assertJson([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'items' => [
                [
                    'size' => 'XL',
                    'color' => 'Red',
                    'status' => 'In Stock',
                    'sku' => 'SKU123',
                    'price' => 19.99,
                ],
                [
                    'size' => 'M',
                    'color' => 'Blue',
                    'status' => 'Out of Stock',
                    'sku' => 'SKU456',
                    'price' => 29.99,
                ],
                // Add more items as needed
            ],
        ]);
    }



    public function testShowProductNotFound()
    {
        $response = $this->get('/api/products/999'); // Assuming there is no product with ID 999
        $response->assertStatus(404);
    }

    // Add more test methods for other controller actions (store, update, destroy) as needed
    // ...

    // Example test for the store method
    public function testStore()
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'items' => [
                [
                    'size' => 'XL',
                    'color' => 'Red',
                    'status' => 'In Stock',
                    'sku' => 'SKU123',
                    'price' => 19.99,
                ],
            ],
        ];

        $response = $this->postJson('/api/products', $data);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Product added successfully']);
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
        $this->assertDatabaseHas('products', ['description' => 'Test Description']);
        $product = Product::where('name', 'Test Product')->first();
        $this->assertDatabaseHas('items', [
            'product_id' => $product->id,
            'size' => 'XL',
            'color' => 'Red',
            'status' => 'In Stock',
            'sku' => 'SKU123',
            'price' => 19.99,
        ]); 
    }
    public function testUpdate()
    {
        // Manually create a product and item in the database
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
        ]);

        $item = Item::create([
            'product_id' => $product->id,
            'size' => 'M',
            'color' => 'Blue',
            'status' => 'In Stock',
            'sku' => 'SKU789',
            'price' => 24.99,
        ]);

        $data = [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'items' => [
                [
                    'id' => $item->id,
                    'size' => 'XL',
                    'color' => 'Blue',
                    'status' => 'In Stock',
                    'sku' => 'SKU789',
                    'price' => 24.99,
                    
                ],
                // Add more items as needed
                // ...
            ],
        ];

        // Send a PUT request to update the product
        $response = $this->putJson('/api/products/' . $product->id, $data);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Optionally, add more assertions based on your application's logic
    }


    // Test destroy method
    public function testDestroy()
    {
        // Assuming you have a product in the database
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
        ]);

        $response = $this->delete('/api/products/' . $product->id);
        $response->assertStatus(200);
        // Add more assertions based on your application's logic
    }

}
