<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\Request;

class ProductScraperController extends Controller
{
    public function scrapeProduct(Request $request)
    {
        $url = $request->input('url');

        // Create a crawler client
        $client = new Client();
        $crawler = $client->request('GET', $url);

        // Attempt scraping using jsonLD schema
        $product = $this->scrapeJsonLdSchema($crawler);

        // If this fails then scrape the structured microdata
        if (empty($product)) {
            $product = $this->scrapeMicrodata($crawler);
        }

        // If all this fails, use conventional scraping
        if (empty($product)) {
            $product = $this->conventionalScrape($crawler);
        }

        return response()->json($product);
    }

    /**
     * Attempt scraping using jsonld schema
     */
    private function scrapeJsonLdSchema($crawler)
    {
        $product = [];

        $schemaData = $crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node, $i) {
            return json_decode($node->text(), true);
        });

        if (count($schemaData) > 0) {
            foreach ($schemaData as $data) {
                if (isset($data['@type']) && $data['@type'] === 'Product') {
                    $product['name'] = $data['name'] ?? null;
                    $product['brand'] = $data['brand']['name'] ?? null;
                    $product['price'] = $data['offers']['price'] ?? null;
                    $product['image'] = $data['image'] ?? null;
                    break;
                }
            }
        }

        return $product;
    }

    /**
     * Attempt scraping structured microdata
     */
    private function scrapeMicrodata($crawler)
    {
        $product = [];

        $product['name'] = $crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="name"]')->extract(['content'])[0] 
                            ?? ($crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="name"]')->count() > 0 
                            ? $crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="name"]')->text() : null);
        $product['brand'] = $crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="brand"]')->extract(['content'])[0]
                            ?? ($crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="brand"]')->count() > 0 
                            ? $crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="brand"]')->text() : null);
        $product['price'] = $crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="price"]')->extract(['content'])[0]
                            ?? ($crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="price"]')->count() > 0 
                            ? $crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="price"]')->text() : null);
        $product['image'] = $crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="image"]')->extract(['content'])[0]
                            ?? ($crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="image"]')->count() > 0 
                            ? $crawler->filter('[itemtype="http://schema.org/Product"] [itemprop="image"]')->attr('src') : null);

        return $product;
    }

    /**
     * Attempt scraping using CSS selectors
     */
    private function conventionalScrape($crawler)
    {
        $product = [];

        if ($crawler->filter('CSS_SELECTOR_NAME')->count() > 0) {
            $product['name'] = $crawler->filter('CSS_SELECTOR_NAME')->text();
        }
        if ($crawler->filter('CSS_SELECTOR_BRAND')->count() > 0) {
            $product['brand'] = $crawler->filter('CSS_SELECTOR_BRAND')->text();
        }
        if ($crawler->filter('CSS_SELECTOR_PRICE')->count() > 0) {
            $product['price'] = $crawler->filter('CSS_SELECTOR_PRICE')->text();
        }
        if ($crawler->filter('CSS_SELECTOR_IMAGE')->count() > 0) {
            $product['image'] = $crawler->filter('CSS_SELECTOR_IMAGE')->attr('src');
        }

        return $product;
    }


}
