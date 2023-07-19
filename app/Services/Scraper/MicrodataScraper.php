<?php

namespace App\Services\Scraper;
use App\Services\Product;

class MicrodataScraper extends Scraper
{
    public function scrape(Product $product): Product
    {
        $productNode = $this->crawler->filterXPath('//*[@itemtype="http://schema.org/Product"]');

        if ($productNode->count() > 0) {
            $product->setName($this->extractContent($productNode, 'name'));
            $product->setBrand($this->extractContent($productNode, 'brand'));
            $product->setPrice($this->extractContent($productNode, 'price'));
            $product->setImage($this->extractContent($productNode, 'image'));
        }

        return $product;
    }

    /**
     * Extract content using XPath
     */
    private function extractContent($node, $property)
    {
        $contentNode = $node->filterXPath("//*[@itemprop='$property']");
        return $contentNode->count() > 0
            ? $contentNode->attr('content')
            : $contentNode->text();
    }
}
