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
            // Look for products in nested schemas
            $productData = $this->recursiveSearch($data, '@type', 'product');
            if (!is_null($productData)) {
                $product->setName($this->findValue($productData, "name", "name"));
                $product->setBrand($this->findValue($productData, "brand", "name"));
                $product->setPrice($productData['price'] ?? $this->recursiveSearch($productData["offers"], 'price'));
                $product->setImage($this->findValue($productData, "image", "thumbnail"));
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


    /**
     * @param array $array
     * @param string $key
     * @param string|null $value if provided, we look for nested arrays not values
     */
    private function recursiveSearch(array $array, string $key, string $value = null)
    {
        $iterator  = new \RecursiveArrayIterator($array);
        $recursive = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($recursive as $k => $v) {
            if ($k === $key) {
                if (!is_null($value)) {
                    if (is_string($v) && strtolower($v) === strtolower($value)) {
                        return $recursive->getInnerIterator()->getArrayCopy();
                    }
                } else {
                    return $v;
                }
            }
        }
        return null;
    }
}
