<?php

namespace App\Http\Controllers;

use App\Services\ProductScraperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductScraperController extends Controller
{
    protected $scraperService;

    public function __construct(ProductScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    public function scrapeProduct(Request $request)
    {
        $url = $request->input('url');

        // Use Laravel's HTTP client to get the HTML content
        // Retry up to 2 times, with a wait time of 3 seconds before giving up
        try {
            $response = retry(2, function () use ($url) {
                return Http::timeout(3)->withHeaders([
                    'User-Agent' => "MyWishlistApp lookup",
                ])->get($url);
            }, 1000);

            // If the request was successful, continue processing...
            if ($response->successful()) {
                $htmlContent = $response->body();

                // Scrape the data using ProductScraperService
                $product = $this->scraperService->scrapeProduct($htmlContent);

                return response()->json($product);
            }

        } catch (\Exception $exception) {
            // If the request was not successful after retrying, return a response indicating the error
            return response()->json(['error' => 'Unable to scrape product at this time. Please try again later.'], 500);
        }
    }


}

