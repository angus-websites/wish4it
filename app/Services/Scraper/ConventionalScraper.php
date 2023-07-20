<?php

namespace App\Services\Scraper;

use App\Services\Product;

abstract class ConventionalScraper extends Scraper
{
    abstract protected function getSelectors(): array;

    public function scrape(Product $product): Product
    {
        $selectors = $this->getSelectors();

        foreach ($selectors as $field => $selector) {
            $value = $this->extractContent($selector);
            if ($value !== null) {
                $setter = 'set' . ucfirst($field);
                if (method_exists($product, $setter)) {
                    $product->$setter($value);
                }
            }
        }
        return $product;
    }

    private function extractContent($selector)
    {
        $node = $this->crawler->filter($selector);

        if ($node->count() > 0) {
            return $node->text();
        }

        return null;
    }
}
