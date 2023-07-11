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
        $this->authorizeResource(WishlistItem::class);
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
        
        // Validate the incoming request data.
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Save
        $wishlist->items()->create($data);

        return Redirect::route('wishlists.show', $wishlist)->with('success', 'Item added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
