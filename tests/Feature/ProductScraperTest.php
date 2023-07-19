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

        // Assert specific values
        $this->assertEquals('Balatapa Hood', $product['name']);
        $this->assertEquals('Idioma', $product['brand']);
        $this->assertEquals('89.0', $product['price']);
        $this->assertEquals('//idioma.world/cdn/shop/products/Balatapa_Hood_Grey_Full_Hung_Web_grande.jpg?v=1679829385', $product['image']);
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

        // Assert specific values
        $this->assertEquals('Bialetti Moka Express Hob Espresso Coffee Maker, 1 Cup', $product['name']);
        $this->assertEquals('Bialetti', $product['brand']);
        $this->assertEquals('24.00', $product['price']);
        $this->assertEquals('http://johnlewis.scene7.com/is/image/JohnLewis/001407520', $product['image']);

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

        // Assert specific values
        $this->assertEquals('Men’s Boreas GTX Mid Walking Boots', $product['name']);
        $this->assertEquals('Scarpa', $product['brand']);
        $this->assertEquals('190', $product['price']);
        $this->assertEquals('https://i1.adis.ws/i/jpl/bl_16243260_a', $product['image']);

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
