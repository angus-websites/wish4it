<?php

namespace App\Services\Scraper;

use App\Services\Product;
use Symfony\Component\DomCrawler\Crawler;

abstract class Scraper
{
    protected $crawler;

    public function __construct($htmlContent)
    {
        $this->crawler = new Crawler($htmlContent);
    }

    abstract public function scrape(Product $product);
}
