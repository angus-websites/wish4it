<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\Request;

class ProductScraperController extends Controller
{
    public function scrapeProduct(Request $request)
    {
        $url = $request->input('url');


        // Create a crawler client
        $client = new Client();
        $crawler = $client->request('GET', $url);

        // Check for jsonld schema file

        // Check for microdata structure

        // Conventional scraping

    }
}
