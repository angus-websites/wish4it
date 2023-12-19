<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    private WishlistService $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    /**
     * A simple endpoint to load a wishlist
     * and its items and return them as a json response
     * @param Wishlist $wishlist
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadWishlist(Wishlist $wishlist)
    {
        $wishlist_resource = $this->wishlistService->fetchWishlistResource($wishlist);
        $wishlist_items = $this->wishlistService->fetchWishlistItemsResource($wishlist, \Auth::user());

        // Return a json response
        return response()->json([
            'wishlist' => $wishlist_resource,
            'items' => $wishlist_items,
        ]);

    }

    /**
     * Load a wishlist and its items and return them as a json response
     * without using route model binding
     */
    public function loadWishlistWithoutRouteModelBinding($wishlist_id)
    {
        $wishlist = $this->wishlistService->fetchWishlist($wishlist_id);
        $wishlist_resource = $this->wishlistService->fetchWishlistResource($wishlist);
        $wishlist_items = $this->wishlistService->fetchWishlistItemsResource($wishlist, \Auth::user());

        // Return a json response
        return response()->json([
            'wishlist' => $wishlist_resource,
            'items' => $wishlist_items,
        ]);
    }

}
