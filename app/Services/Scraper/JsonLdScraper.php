<?php

namespace App\Services\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use App\Services\Product;

class JsonLdScraper extends Scraper
{
    public function scrape(Product $product)
    {

        $schemaData = $this->crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node) {
            return json_decode($node->text(), true);
        });

        foreach ($schemaData as $data) {
            if (isset($data['@type']) && strtolower($data['@type']) === 'product') {
                $product->setName($data['name'] ?? null);
                $product->setBrand($data['brand']['name'] ?? null);
                $product->setPrice($data['offers']['price'] ?? null);
                $product->setImage($data['image'] ?? null);
                break;
            }
        }

        return $product;
    }

}
