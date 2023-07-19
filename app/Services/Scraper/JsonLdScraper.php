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
                $product->setName($this->findValue($data, "name", "name"));
                $product->setBrand($this->findValue($data, "brand", "name"));
                $product->setPrice($data['price'] ?? $this->recursiveSearch($data["offers"], 'price'));
                $product->setImage($this->findValue($data, "image", "name"));
                break;
            }
        }

        return $product;
    }

    private function findValue($data, $key, $secondKey){
        // First look at direct children
        if (array_key_exists($key, $data)){
            $value = $data[$key];
            // Check if not nested
            if (!is_array($value)) {

                // If not nested then return the value
                return $value;
            }

            // If nested, look inside recursively with the second key
            else{
                return $this->recursiveSearch($data[$key], $secondKey);
            }
        }
        return $this->recursiveSearch($data, $key);
    }

    private function recursiveSearch(array $array, string $key)
    {
        $iterator  = new \RecursiveArrayIterator($array);
        $recursive = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($recursive as $k => $v) {
            if ($k === $key) {
                return $v;
            }
        }
        return null;
    }
}
