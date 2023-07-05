<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Resources\WishlistResource;

class WishlistController extends Controller
{
    /**
     * Wishlist index page
     */
    public function index(Request $request){
        return Inertia::render('Lists', [
            'lists' => WishlistResource::collection(Auth::user()->wishlists()->get())
        ]);
    }
}
