<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    
    

    public function it_can_store_a_new_product_through_http()
    {
        // Define the data to be sent in the request
        $requestData = [
            'name' => 'New Product',
            'description' => 'Description of the new product',
            'items' => [
                [
                    'size' => 'Large',
                    'color' => 'Red',
                    'status' => 'Available',
                    'sku' => 'SKU123',
                    'price' => 10.99,
                ]
            ]
        ];

        // Make a POST request to the store endpoint with the data
        $response = $this->json('POST', '/api/products', $requestData);

        // Assert the response status is 201 (Created)
        $response->assertStatus(201);

        // Assert that the product was added to the database
        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'description' => 'Description of the new product',
        ]);

        // Assert that the response contains the expected data
        $response->assertJsonStructure([
            'message',
            'id'
        ]);
    }
    // Write similar tests for other controller methods (show, store, update, destroy)
}
