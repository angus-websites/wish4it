<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WishlistController extends Controller
{
    /**
     * Wishlist index page
     */
    public function index(Request $request){
        return Inertia::render('Lists', [
            'lists' => Auth::user()->wishlists()
        ]);
    }
}
