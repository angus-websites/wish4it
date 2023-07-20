<?php

namespace App\Services\Scraper;

class AmazonScraper extends ConventionalScraper
{
    protected function getSelectors(): array
    {
        return [
            'name' => '#productTitle',
            'price' => '#sns-base-price',
            'image' => '#landingImage',
        ];
    }
}
