<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Services\ProductScraperService;

class ProductScraperTest extends TestCase
{
    use WithoutMiddleware;


    /** Test scraping for a hoodie at a shopify
     * website
     */
    public function test_hoodie_scrape(): void
    {
        // https://idioma.world/collections/sweatshirts-he/products/balatapa-hood

        // Get the stored HTML content
        $htmlContent = file_get_contents(base_path('tests/html/hoodie.html'));

        // Create a scraping service
        $service = new ProductScraperService();
        $product = $service->scrapeProduct($htmlContent);
        
        // Expected
        $expected = [
            "name" => "Balatapa Hood",
            "brand" => "Idioma",
            "price" => "89.0",
            "image" => "//idioma.world/cdn/shop/products/Balatapa_Hood_Grey_Full_Hung_Web_grande.jpg?v=1679829385"
        ];

        // Assert specific values
        $this->assertEqualsCanonicalizing($expected, $product);
    }

    /**
     * Testing the scraping of a coffee thing  on John Lewis
     */
    public function test_john_lewis_scrape()
    {

        // https://www.johnlewis.com/bialetti-moka-express-hob-espresso-coffee-maker/p1407523

        // Get the stored HTML content
        $htmlContent = file_get_contents(base_path('tests/html/coffee.html'));

        // Create a scraping service
        $service = new ProductScraperService();
        $product = $service->scrapeProduct($htmlContent);

        // Expected
        $expected = [
            "name" => "Bialetti Moka Express Hob Espresso Coffee Maker, 1 Cup",
            "brand" => "Bialetti",
            "price" => "24.00",
            "image" => "http://johnlewis.scene7.com/is/image/JohnLewis/001407520"
        ];

        // Assert specific values
        $this->assertEqualsCanonicalizing($expected, $product);

    }

    /**
     * Testing the scraping of a pair of walking boots at GO OUTDOORS
     */
    public function test_go_outdoors_scrape()
    {

        // https://www.gooutdoors.co.uk/16243260/scarpa-mens-boreas-gtx-mid-walking-boots-16243260

        // Get the stored HTML content
        $htmlContent = file_get_contents(base_path('tests/html/boots.html'));

        // Create a scraping service
        $service = new ProductScraperService();
        $product = $service->scrapeProduct($htmlContent);

        // Expected
        $expected = [
            "name" => "Men’s Boreas GTX Mid Walking Boots",
            "brand" => "Scarpa",
            "price" => "190",
            "image" => "https://i1.adis.ws/i/jpl/bl_16243260_a"
        ];

        // Assert specific values
        $this->assertEqualsCanonicalizing($expected, $product);


    }

    /**
     * Testing the scraping of an iphone at Apple
     */
    public function test_apple_store_scrape()
    {

        // https://www.apple.com/uk/shop/buy-iphone/iphone-14-pro/6.1-inch-display-256gb-silver

        // Get the stored HTML content
        $htmlContent = file_get_contents(base_path('tests/html/iphone.html'));

        // Create a scraping service
        $service = new ProductScraperService();
        $product = $service->scrapeProduct($htmlContent);

        // Expected
        $expected = [
            "name" => "iPhone 14 Pro 256GB Silver",
            "brand" => null,
            "price" => "1209",
            "image" => "https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/iphone-14-pro-finish-select-202209-6-1inch-silver?wid=2560&hei=1440&fmt=jpeg&qlt=95&.v=1663703840488"
        ];

        // Assert specific values
        $this->assertEqualsCanonicalizing($expected, $product);
    }


    /**
     * Testing the scraping of a camera on Argos
     */
    public function test_argos_scrape()
    {
        // https://www.argos.co.uk/product/7263259

        // Get the stored HTML content
        $htmlContent = file_get_contents(base_path('tests/html/camera.html'));

        // Create a scraping service
        $service = new ProductScraperService();
        $product = $service->scrapeProduct($htmlContent);

        // Assert specific values
        $this->assertEquals('Sony Full Frame A7mk3 Camera with SEL2870 Lens ', $product['name']);
        $this->assertEquals('Sony', $product['brand']);
        $this->assertEquals('1699.99', $product['price']);
        $this->assertEquals('https://i1.adis.ws/i/jpl/bl_16243260_a', $product['image']);

    }



}
