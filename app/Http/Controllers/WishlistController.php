<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;

class WishlistController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(Wishlist::class);
    }

    /**
     * Wishlist index page
     */
    public function index(Request $request){
        return Inertia::render('Wishlist/Index', [
            'lists' => WishlistResource::collection(Auth::user()->wishlists()->get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(Wishlist $wishlist){

        // Load the items of this wishlist
        $wishlist->load('items');

        // Convert to an API resource
        $list = new WishlistResource($wishlist);

        return Inertia::render('Wishlist/View', [
            'list' => $list,
            'can' => [
                'deleteEntry' => Auth::user()->can('delete', $list),
                'editEntry' => Auth::user()->can('update', $list),
            ],
        ]);
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
