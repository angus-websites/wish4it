<?php

namespace App\Services;

use App\Services\Scraper\AmazonScraper;
use App\Services\Scraper\JsonLdScraper;
use App\Services\Scraper\MicrodataScraper;
use Illuminate\Support\Facades\Log;

class ProductScraperService
{
    public function scrapeProduct($htmlContent, $url = null): array
    {

        // Create our scrapers
        $scrapers = [
            new JsonLdScraper($htmlContent),
            new MicrodataScraper($htmlContent),
            new AmazonScraper($htmlContent),
        ];

        // Create a product instance
        $product = new Product();

        foreach ($scrapers as $scraper) {
            $scraper->scrape($product);

            // Continue scraping until no fields are missing
            $missingFields = $product->getMissingFields();
            if (empty($missingFields)) {
                break;
            }
        }

        // If we didn't scrape all the data then log it
        if (! empty($missingFields)) {
            // Log missing fields to PHP's error log
            $this->logMissingFields($missingFields, $url);
        }

        return $product->toArray();
    }

    /**
     * If the scraper cannot find certian fields, log them
     */
    private function logMissingFields(array $missingFields, $url = null): void
    {
        $missingFieldsString = implode(', ', $missingFields);
        $urlString = $url ? " URL: $url" : 'N/A';
        Log::channel('scraperIncomplete')->info($missingFieldsString.$urlString);
    }
}
