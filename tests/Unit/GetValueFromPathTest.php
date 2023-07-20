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

        $brand = $scraper->getValueFromPath($jsonLdData, ["brand", "name"]);
        $this->assertEquals("Test Brand", $brand);

    }

    public function testVeryNestedBrand(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            "brand" => [
                "data" => [
                    "moreData" => [
                        "name" => "Test Brand"
                    ]
                ],
            ],
            "price" => "30.00"
        ];

        $scraper = new JsonLdScraper(null);

        $brand = $scraper->getValueFromPath($jsonLdData, ["brand", "*", "name"]);
        $this->assertEquals("Test Brand", $brand);

    }

    public function testNormalPrice(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            "price" => "30.00"
        ];

        $scraper = new JsonLdScraper(null);

        $price = $scraper->getValueFromPath($jsonLdData, ["price"]);
        $this->assertEquals("30.00", $price);

    }

    public function testPriceInOffers(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            "offers" => [
                "@type" => "Offer",
                "price" => "12.00",
                "priceCurrency" => "GBP",
            ]
        ];

        $scraper = new JsonLdScraper(null);

        $price = $scraper->getValueFromPath($jsonLdData, ["offers", "price"]);
        $this->assertEquals("12.00", $price);

    }

    public function testPriceInNestedOffers(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            "offers" => [
                "@type" => "OfferParent",
                "priceCurrency" => "GBP",
                "offers" => [
                    "@type" => "Offer",
                    "price" => "15.00",
                    "priceCurrency" => "GBP",
                ]
            ]
        ];

        $scraper = new JsonLdScraper(null);

        $price = $scraper->getValueFromPath($jsonLdData, ["offers", "*", "price"]);
        $this->assertEquals("15.00", $price);

    }

    public function testPriceInNestedOffersArray(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            "offers" => [
                "@type" => "AggregateOffer",
                "priceCurrency" => "GBP",
                "offerCount" => 2,
                "offers" => [
                    [
                        "@type" => "Offer",
                        "price" => "15.00",
                        "priceCurrency" => "GBP",
                    ],
                    [
                        "@type" => "Offer",
                        "price" => "38.00",
                        "priceCurrency" => "GBP",
                    ],
                ]
            ]
        ];

        $scraper = new JsonLdScraper(null);

        $price = $scraper->getValueFromPath($jsonLdData, ["offers", "*", "price"]);
        $this->assertEquals("15.00", $price);

    }

    public function testNormalImage(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            "image" => "test.jpg"
        ];

        $scraper = new JsonLdScraper(null);

        $image = $scraper->getValueFromPath($jsonLdData, ["image"]);
        $this->assertEquals("test.jpg", $image);

    }

    public function testNestedImage(): void
    {
        $jsonLdData = [
            "name" => "Test Product",
            'image' => [
                [
                    '@type' => 'ImageObject',
                    'thumbnail' => 'test.jpg',
                ],
            ],
        ];

        $scraper = new JsonLdScraper(null);

        $image = $scraper->getValueFromPath($jsonLdData, ['image','*','thumbnail']);
        $this->assertEquals("test.jpg", $image);

    }
}
