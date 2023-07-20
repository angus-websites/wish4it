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
        foreach ($path as $keyIndex => $key) {
            if ($key === '*') {
                if (is_array($current)) {
                    foreach ($current as $item) {
                        if (is_array($item)) {
                            $remainingPath = array_slice($path, $keyIndex + 1);
                            if (isset($remainingPath[0]) && array_key_exists($remainingPath[0], $item)) {
                                return $item[$remainingPath[0]];
                            }
                            $result = $this->findInPath($item, $remainingPath);
                            if ($result !== null) {
                                return $result;
                            }
                        } elseif (is_object($item)) {
                            $remainingPath = array_slice($path, $keyIndex + 1);
                            if (isset($remainingPath[0]) && property_exists($item, $remainingPath[0])) {
                                return $item->{$remainingPath[0]};
                            }
                            $result = $this->findInPath(get_object_vars($item), $remainingPath);
                            if ($result !== null) {
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
