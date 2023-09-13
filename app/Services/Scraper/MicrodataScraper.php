<?php

namespace App\Services\Scraper;

use App\Services\Product;

class MicrodataScraper extends Scraper
{
    public function scrape(Product $product): Product
    {
        $productNode = $this->crawler->filterXPath('//*[contains(@itemtype, "schema.org/Product")]');

        if ($productNode->count() > 0) {
            $product->setName($this->extractContent($productNode, 'name', 'Product'));
            $product->setBrand($this->extractContent($productNode, 'brand', 'Product'));
            $product->setPrice($this->extractContent($productNode, 'price', 'Offer'));
            $product->setImage($this->extractContent($productNode, 'image', 'Product'));
        }

        return $product;
    }

    private function extractContent($node, $property, $itemtype)
    {
        // Get all nodes with the correct itemprop, regardless of their itemtype
        $contentNodes = $node->filterXPath(".//*[@itemprop='$property']");

        foreach ($contentNodes as $contentNode) {
            $parent = $contentNode->parentNode;

            while ($parent !== null && $parent->nodeName !== '#document') {
                // If the parent node is of the correct type, we've found a direct child.
                if ($parent->hasAttribute('itemtype') && stripos($parent->getAttribute('itemtype'), $itemtype) !== false) {
                    // First try the content attribute
                    $content = $contentNode->getAttribute('content');

                    // If no content attribute, try the text content of the node
                    if (empty($content)) {
                        $content = $contentNode->textContent;
                    }

                    return $content;
                }

                // If the parent node has a different itemtype, this is not a direct child.
                if ($parent->hasAttribute('itemtype') && stripos($parent->getAttribute('itemtype'), $itemtype) === false) {
                    break;
                }

                $parent = $parent->parentNode;
            }
        }

        return null;
    }
}
