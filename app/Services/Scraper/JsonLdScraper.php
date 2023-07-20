<?php

namespace App\Services\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use App\Services\Product;

class JsonLdScraper extends Scraper
{
    // Define possible paths for each attribute
    private $paths = [
        'name' => [['name']],
        'brand' => [['brand', 'name'], ["brand"]],
        'price' => [['offers', '*', 'price'], ['offers', 'price'], ['price']],
        'image' => [['image','*','thumbnail'], ['image']],
    ];



    public function scrape(Product $product)
    {
        // Look for occurrences for jsonld schemas in the html
        $schemaData = $this->crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node) {
            return json_decode($node->text(), true);
        });

        // Look for a product schema
        foreach ($schemaData as $data) {
            $productData = $this->recursiveSearch($data, '@type', 'product');

            // Assign product data from the json
            if (!is_null($productData)) {
                $product->setName($this->findValue($productData, "name"));
                $product->setBrand($this->findValue($productData, "brand"));
                $product->setPrice($this->findValue($productData, "price"));
                $product->setImage($this->findValue($productData, "image"));
                break;
            }
        }

        return $product;
    }

    private function findValue($data, $key)
    {
        $paths = $this->paths[$key];
        foreach ($paths as $path) {
            $value = $this->getValueFromPath($data, $path);
            if ($value !== null) {
                return $value;
            }
        }
        return null;
    }

    public function getValueFromPath($data, $path) {

        // Base case: if path is empty, return the data if it's a leaf node, else return null
        if (empty($path)) {
            return is_array($data) ? null : $data;
        }

        // If data is not an array (but there's still some path left), return null
        if (!is_array($data)) {
            return null;
        }

        // Get the current key
        $key = array_shift($path);

        // Handle wildcard: continue the search in the current subtree
        if ($key === '*') {
            foreach ($data as $subdata) {
                $result = $this->getValueFromPath($subdata, $path);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        // If the key exists in the data, continue the search in the corresponding subtree
        else if (array_key_exists($key, $data)) {
            return $this->getValueFromPath($data[$key], $path);
        }

        // If the key does not exist, return null
        return null;
    }


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
