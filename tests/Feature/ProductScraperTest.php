<?php
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ScrapeProductTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_scrapes_product_data_correctly()
    {
        // Get the stored HTML content
        $htmlContent = file_get_contents(base_path('tests/html/sample-product.html'));

        // Mock the HTTP request to return your stored HTML content
        Http::fake([
            '*' => Http::response($htmlContent, 200),
        ]);

        $response = $this->postJson('/api/scrape', [
            'url' => 'https://www.example.com/sample-product'
        ]);

        // Assert that the response has the correct structure
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'name',
                    'brand',
                    'price',
                    'image'
                 ]);
    }
}
