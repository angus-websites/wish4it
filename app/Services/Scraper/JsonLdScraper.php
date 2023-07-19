<?php

namespace App\Services\Scraper;

use Symfony\Component\DomCrawler\Crawler;

class JsonLdScraper extends Scraper
{
    public function scrape()
    {
        $product = [];

        $schemaData = $this->crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node) {
            return json_decode($node->text(), true);
        });

        foreach ($schemaData as $data) {
            if (isset($data['@type']) && strtolower($data['@type']) === 'product') {
                $product = $this->extractProductDetails($data);
                break;
            }
        }

        return $product;
    }

    /**
     * Extract product details
     */
    private function extractProductDetails($data)
    {
        $product['name'] = $data['name'] ?? null;
        $product['brand'] = $data['brand']['name'] ?? null;
        $product['price'] = $data['offers']['price'] ?? null;
        $product['image'] = $data['image'] ?? null;

        // Additional product details can be extracted here

        return $product;
    }
}
