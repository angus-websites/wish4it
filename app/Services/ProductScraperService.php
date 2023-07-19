<?php

namespace App\Services;

use App\Services\Scraper\ConventionalScraper;
use App\Services\Scraper\JsonLdScraper;
use App\Services\Scraper\MicrodataScraper;

class ProductScraperService
{
    /**
     * Scrape product details from the HTML content.
     */
    public function scrapeProduct($htmlContent)
    {
        $scrapers = [
            new JsonLdScraper($htmlContent),
            new MicrodataScraper($htmlContent),
            new ConventionalScraper($htmlContent)
        ];

        foreach ($scrapers as $scraper) {
            $product = $scraper->scrape();

            if (!empty($product)) {
                return $product;
            }
        }

        return [];
    }








}
