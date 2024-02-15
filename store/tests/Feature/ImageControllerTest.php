<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;

class ImageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Seed the database with images
        $images = Image::factory(5)->create();

        // Make a GET request to the index endpoint
        $response = $this->get('/images');

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the response contains the images
        $response->assertJson($images->toArray());
    }
}
