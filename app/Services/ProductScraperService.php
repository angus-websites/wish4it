<?php

namespace App\Services;

use App\Services\Scraper\ConventionalScraper;
use App\Services\Scraper\JsonLdScraper;
use App\Services\Scraper\MicrodataScraper;

class ProductScraperService
{

    public function scrapeProduct($htmlContent): array
    {

        // Create our scrapers
        $scrapers = [
            new JsonLdScraper($htmlContent),
            new MicrodataScraper($htmlContent),
        ];

        // Create a product instance
        $product = new Product();

        foreach ($scrapers as $scraper) {
            $scraper->scrape($product);

            if ($product->isComplete()) {
                break;
            }
        }

        return $product->toArray();
    }

}
