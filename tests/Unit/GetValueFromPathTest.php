<?php

namespace Tests\Unit;

use App\Services\Scraper\JsonLdScraper;
use PHPUnit\Framework\TestCase;

class GetValueFromPathTest extends TestCase
{
    public function testName(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            "brand" => [
                "name" => "Test Brand",
            ]
        ];

        $scraper = new JsonLdScraper(null);

        $name = $scraper->getValueFromPath($jsonLdData, ["name"]);
        $this->assertEquals("Test Product", $name);

    }

    public function testNestedBrand(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            "brand" => [
                "name" => "Test Brand",
            ],
            "price" => "30.00"
        ];

        $scraper = new JsonLdScraper(null);

        $name = $scraper->getValueFromPath($jsonLdData, ["brand", "name"]);
        $this->assertEquals("Test Brand", $name);

    }
}
