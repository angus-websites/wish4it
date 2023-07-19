<?php
namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class ProductScraperService
{
    public function scrapeProduct($htmlContent)
    {
        // Create a Crawler instance and add HTML content
        $crawler = new Crawler($htmlContent);

        // Attempt scraping using jsonLD schema
        $product = $this->scrapeJsonLdSchema($crawler);

        // If this fails then scrape the structured microdata
        if (empty($product)) {
            $product = $this->scrapeMicrodata($crawler);
        }

        // If all this fails, use conventional scraping
        if (empty($product)) {
            $product = $this->conventionalScrape($crawler);
        }

        return $product;
    }

    /**
     * Attempt scraping using jsonld schema
     */
    private function scrapeJsonLdSchema($crawler)
    {
        $product = [];

        $schemaData = $crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node, $i) {
            return json_decode($node->text(), true);
        });

        if (count($schemaData) > 0) {
            foreach ($schemaData as $data) {
                if (isset($data['@type']) && strtolower($data['@type']) === 'product') {
                    $product['name'] = $data['name'] ?? null;
                    $product['brand'] = $data['brand']['name'] ?? null;
                    $product['price'] = $data['offers']['price'] ?? null;
                    $product['image'] = $data['image'] ?? null;
                    break;
                }
            }
        }

        return $product;
    }

    /**
     * Attempt scraping structured microdata
     */
    private function scrapeMicrodata($crawler)
    {
        $product = [];

        $productNode = $crawler->filterXPath('//*[@itemtype="http://schema.org/Product"]');

        if ($productNode->count() > 0) {
            $product['name'] = $productNode->filterXPath('//*[@itemprop="name"]')->count() > 0
                                ? $productNode->filterXPath('//*[@itemprop="name"]')->attr('content')
                                : $productNode->filterXPath('//*[@itemprop="name"]')->text();
            $product['brand'] = $productNode->filterXPath('//*[@itemprop="brand"]')->count() > 0
                                ? $productNode->filterXPath('//*[@itemprop="brand"]')->attr('content')
                                : $productNode->filterXPath('//*[@itemprop="brand"]')->text();
            $product['price'] = $productNode->filterXPath('//*[@itemprop="price"]')->count() > 0
                                ? $productNode->filterXPath('//*[@itemprop="price"]')->attr('content')
                                : $productNode->filterXPath('//*[@itemprop="price"]')->text();
            $product['image'] = $productNode->filterXPath('//*[@itemprop="image"]')->count() > 0
                                ? $productNode->filterXPath('//*[@itemprop="image"]')->attr('content')
                                : $productNode->filterXPath('//*[@itemprop="image"]')->attr('src');
        }

        return $product;
    }



    /**
     * Attempt scraping using CSS selectors
     */
    private function conventionalScrape($crawler)
    {
        $product = [];

        if ($crawler->filter('CSS_SELECTOR_NAME')->count() > 0) {
            $product['name'] = $crawler->filter('CSS_SELECTOR_NAME')->text();
        }
        if ($crawler->filter('CSS_SELECTOR_BRAND')->count() > 0) {
            $product['brand'] = $crawler->filter('CSS_SELECTOR_BRAND')->text();
        }
        if ($crawler->filter('CSS_SELECTOR_PRICE')->count() > 0) {
            $product['price'] = $crawler->filter('CSS_SELECTOR_PRICE')->text();
        }
        if ($crawler->filter('CSS_SELECTOR_IMAGE')->count() > 0) {
            $product['image'] = $crawler->filter('CSS_SELECTOR_IMAGE')->attr('src');
        }

        return $product;
    }
}
