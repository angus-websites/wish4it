<?php

namespace App\Services\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use App\Services\Product;

class JsonLdScraper extends Scraper
{
    // Define possible paths for each attribute, note more complex routes should be defined first
    // to avoid returning nested data
//    private $paths = [
//        'name' => [['name']],
//        'brand' => [['brand', 'name']],
//        'price' => [['offers', 'price'], ['price']],
//        'image' => [['image', 'thumbnail'], ['image']],
//    ];

    private $paths = [
        'name' => [['name']],
        'brand' => [['brand', 'name']],
        'price' => [['offers', '*', 'price'], ['offers', 'price'], ['price']],
        'image' => [['image','*','thumbnail'], ['image']],
    ];



    public function scrape(Product $product)
    {
        $schemaData = $this->crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node) {
            return json_decode($node->text(), true);
        });

        foreach ($schemaData as $data) {
            $productData = $this->recursiveSearch($data, '@type', 'product');
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
            $value = $this->findInPath($data, $path);
            if ($value !== null) {
                return $value;
            }
        }
        return null;
    }

    private function findInPath(array $data, array $path)
    {
        $current = $data;
        foreach ($path as $key) {
            if ($key === '*') {
                if (is_array($current)) {
                    foreach ($current as $item) {
                        if (is_array($item)) {
                            $result = $this->findInPath($item, array_slice($path, 1));
                            if ($result) {
                                return $result;
                            }
                        } elseif (is_object($item)) {
                            $result = $this->findInPath(get_object_vars($item), array_slice($path, 1));
                            if ($result) {
                                return $result;
                            }
                        }
                    }
                }
                return null;
            } else {
                if (!isset($current[$key])) {
                    return null;
                }
                $current = $current[$key];
            }
        }
        return is_array($current) ? null : $current;
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
