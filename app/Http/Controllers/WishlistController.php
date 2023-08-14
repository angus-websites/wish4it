<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Redirect;

class WishlistController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['show']);
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
        // Validate the incoming request data.
        $data = $request->validate([
            'title' => 'required|string|max:30',
            'public' => 'required|boolean',
        ]);

        // Save
        Auth::user()->createWishlist($data);
        return Redirect::route('wishlists.index')->with('success', 'Wishlist created');
    }


    /**
     * Display the specified resource.
     */
    public function show(Wishlist $wishlist){

        // Load the items of this wishlist
        $wishlist->load('items');

        $currentUserId = Auth::id(); // This will return null if no user is authenticated

        // Filter the items where needs is greater than has (remove the purchased items, unless author)
        if (!Auth::check() || !Auth::user()->can('viewPurchased', $wishlist)) {
            $wishlist->items = $wishlist->items->filter(function ($item) {
                return $item->needs > $item->has;
            });
        }


        // Convert to an API resource
        $list = new WishlistResource($wishlist);

        // If user not logged in then render a different view
        if (!$currentUserId) {

            // Store the intended URL so if user creates an account they are redirected here
            session(['url.intended' => url()->current()]);

            return Inertia::render('Guest/WishlistPublic', [
                'list' => $list,
            ]);
        }

        // Return to the authenticated view
        return Inertia::render('Wishlist/View', [
            'list' => $list,
            'can' => [
                'deleteList' => Auth::user()->can('delete', $wishlist),
                'editList' => Auth::user()->can('update', $wishlist),
                'createItems' => Auth::user()->can('create',  [WishlistItem::class, $wishlist]),
                'viewPurchased' => Auth::user()->can('viewPurchased',  $wishlist),
            ],
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        // Validate the incoming request data.
        $data = $request->validate([
            'title' => 'required|string|max:30',
            'public' => 'required|boolean',
        ]);

        // Save
        $wishlist->fill($data);
        $wishlist->save();

        // If we pass validation
        return Redirect::back()->with('success', 'Wishlist updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return Redirect::route('wishlists.index')->with('info', 'Wishlist deleted');
    }
}
