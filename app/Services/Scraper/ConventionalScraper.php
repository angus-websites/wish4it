<?php

namespace App\Services\Scraper;

class ConventionalScraper extends Scraper
{
    public function scrape()
    {
        $product = [];
        $productSelectors = ['name' => 'CSS_SELECTOR_NAME', 'brand' => 'CSS_SELECTOR_BRAND', 'price' => 'CSS_SELECTOR_PRICE', 'image' => 'CSS_SELECTOR_IMAGE'];

        foreach ($productSelectors as $key => $selector) {
            $product[$key] = $this->crawler->filter($selector)->count() > 0 ? $this->crawler->filter($selector)->text() : null;
        }

        return $product;
    }
}
