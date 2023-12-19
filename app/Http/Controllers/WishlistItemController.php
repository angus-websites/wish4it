<?php

namespace App\Http\Controllers;

use App\Enums\MarkAsPurchasedStatusEnum;
use App\Models\Reservation;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class WishlistItemController extends Controller
{
    private WishlistService $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->middleware('auth:sanctum');

        // Authorize everything through the parent wishlist
        $this->authorizeResource(Wishlist::class);

        // Create a new wishlist service
        $this->wishlistService = $wishlistService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Wishlist $wishlist)
    {

        // Authorize this method manually
        $this->authorize('create', [WishlistItem::class, $wishlist]);

        // Validate the incoming request data.
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'url' => 'nullable|url',
            'comment' => 'nullable|string|max:500',
            'needs' => 'required|integer|min:1',
            'image' => 'nullable|string',
        ]);

        // Save
        $this->wishlistService->storeWishlistItem($wishlist, $data);

        return Redirect::route('wishlists.show', $wishlist)->with('success', 'Item added');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wishlist $wishlist, WishlistItem $item)
    {

        // Validate the incoming request data.
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'brand' => 'nullable|string|max:30',
            'price' => 'nullable|numeric|min:0',
            'url' => 'nullable|url',
            'comment' => 'nullable|string|max:100',
            'needs' => 'required|integer|min:1',
        ]);

        // Save
        $this->wishlistService->updateWishlistItem($item, $data);

        // If we pass validation
        return Redirect::back()->with('success', 'Item updated');
    }

    public function markAsPurchased(Request $request, Wishlist $wishlist, WishlistItem $item)
    {

        // Authorise marking this item as purchased
        $this->authorize('markAsPurchased', [$wishlist]);

        // Validate
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'has' => 'sometimes|integer'
        ]);

        // Check the client has value matches the server value (to check for out of date data)
        $response = $this->wishlistService->markAsPurchased(Auth::user(), $item, $request->quantity, $request->has ?? 0);

        // Deal with the response
        return match ($response) {
            MarkAsPurchasedStatusEnum::SUCCESS => Redirect::back()->with('success', 'Marked as purchased'),
            MarkAsPurchasedStatusEnum::ALREADY_PURCHASED => Redirect::back()->withErrors(['alreadyPurchased' => 'This item has already been marked as purchased and is no longer available.']),
            MarkAsPurchasedStatusEnum::HAS_CHANGED => Redirect::back()->withErrors(['hasChanged' => 'The number of purchases for this item has already changed. Please try again']),
            default => Redirect::back()->withErrors(['error' => 'An unknown error occurred. Please try again']),
        };

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Wishlist $wishlist, WishlistItem $item)
    {
        $this->wishlistService->deleteWishlistItem($item);

        return Redirect::back()->with('success', 'Item deleted');
    }
}
