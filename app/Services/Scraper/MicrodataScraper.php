<?php

namespace App\Services\Scraper;

class MicrodataScraper extends Scraper
{
    public function scrape()
    {
        $product = [];

        $productNode = $this->crawler->filterXPath('//*[@itemtype="http://schema.org/Product"]');

        if ($productNode->count() > 0) {
            $product['name'] = $this->extractContent($productNode, 'name');
            $product['brand'] = $this->extractContent($productNode, 'brand');
            $product['price'] = $this->extractContent($productNode, 'price');
            $product['image'] = $this->extractContent($productNode, 'image');
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
