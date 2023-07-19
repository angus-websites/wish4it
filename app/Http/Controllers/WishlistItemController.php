<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Redirect;

class WishlistItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');

        // Authorize everyting through the parent wishlist
        $this->authorizeResource(Wishlist::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        ]);

        // Save
        $wishlist->items()->create($data);

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
        $item->fill($data);
        $item->wishlist_id=$wishlist->id;
        $item->save();

        // If we pass validation
        return Redirect::back()->with('success', 'Item updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Wishlist $wishlist, WishlistItem $item)
    {
        $item->delete();
        return Redirect::back()->with('success', 'Item deleted');
    }
}
