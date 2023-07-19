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
        $response = Http::get($url);
        $htmlContent = $response->body();

        // Scrape the data using ProductScraperService
        $product = $this->scraperService->scrapeProduct($htmlContent);

        return response()->json($product);
    }
}

