<?php

namespace App\Services\Scraper;
use App\Services\Product;

class ConventionalScraper extends Scraper
{
    public function scrape(Product $product): Product
    {
        return $product;
    }
}
