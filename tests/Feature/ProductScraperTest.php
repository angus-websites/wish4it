<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductScraperTest extends TestCase
{
    /**
     * Test the product scraper endpoint.
     *
     * @return void
     */
    public function testProductScraper()
    {
        $response = $this->postJson('/scrape-product', ['url' => 'https://idioma.world/collections/sweatshirts-he/products/balatapa-hood']);

        echo($response);
//        $response
//            ->assertStatus(200)
//            ->assertJson([
//                'name' => 'Example Product Name',
//                'brand' => 'Example Brand',
//                'price' => 'Example Price',
//                'image' => 'Example Image URL'
//            ]);
    }
}
