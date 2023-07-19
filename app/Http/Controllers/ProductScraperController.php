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

        
    }
}
