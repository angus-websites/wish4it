<?php

namespace App\Http\Controllers;

use App\Services\ProductScraperService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Validator;


class ProductScraperController extends Controller
{
    private ProductScraperService $scraperService;

    public function __construct(ProductScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    public function scrapeProduct(Request $request): JsonResponse
    {

        // Validate the input
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Extract the input from the request
        $url = $request->input('url');

        try {

            // Try to connect 3 times before giving up
            $response = Http::timeout(3)->withHeaders([
                'User-Agent' => "MyWishlistApp lookup",
            ])->retry(2, 1000)->get($url);


            // Continue if we succesfully connect to the url
            if ($response->successful()) {
                $htmlContent = $response->body();
                $product = $this->scraperService->scrapeProduct($htmlContent, $url);
                return response()->json(["product" => $product]);
            }
            else {

                Log::channel("scraper")->error('Could not receive html data for scraping', [
                    'url' => $url,
                    'status_code' => $response->status(),
                ]);
            }

        }
        catch (RequestException $exception) {
            Log::channel("scraper")->error('A request error occurred during URL scraping', [
                'url' => $url,
                'error_message' => $exception->getMessage(),
            ]);

            return response()->json(['error' => 'Unable to scrape product at this time. Please try again later.'], 500);
        }
        catch (ConnectionException $exception) {

            Log::channel("scraper")->error('A connection error occurred during URL scraping', [
                'url' => $url,
                'error_message' => $exception->getMessage(),
            ]);

            return response()->json(['error' => 'Unable to connect to this url at this time. Please try again later.'], 500);
        }
        catch (Exception $exception) {
            Log::channel("scraper")->error('A unknown error occurred during URL scraping', [
                'url' => $url,
                'error_message' => $exception->getMessage(),
            ]);

            return response()->json(['error' => 'Unable to scrape product at this time. Please try again later.'], 500);
        }

        return response()->json(['error' => 'Unable to scrape this website'], 500);
    }
}
