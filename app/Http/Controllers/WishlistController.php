<?php

namespace App\Http\Controllers;

use App\Http\Resources\WishlistItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

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

        $currentUserId = Auth::id();

        // Initialize the query builder for items
        $itemsCollection = $wishlist->items()->get();

        // If the user cannot view purchased items, then filter them out.
        if (!Auth::check() || !Auth::user()->can('viewPurchased', $wishlist)) {
            $itemsCollection = $itemsCollection->filter(function ($item) {
                return $item->needs > $item->has;
            });
        }

        // Transform the collection to a query builder (so we can paginate)
        $itemsQuery = $itemsCollection->toQuery();

        // Now paginate and convert the items to a resource
        $items = WishlistItemResource::collection($itemsQuery->paginate(16)->withQueryString());

        // Convert the wishlist itself to an API resource
        $list = new WishlistResource($wishlist);

        // If user not logged in then render a different view
        if (!$currentUserId) {

            // Store the intended URL so if user creates an account they are redirected here
            session(['url.intended' => url()->current()]);

            return Inertia::render('Guest/WishlistPublic', [
                'list' => $list,
                'items' => $items,
            ]);
        }

        // Return to the authenticated view
        return Inertia::render('Wishlist/View', [
            'list' => $list,
            'items' => $items,
            'can' => [
                'deleteList' => Auth::user()->can('delete', $wishlist),
                'editList' => Auth::user()->can('update', $wishlist),
                'createItems' => Auth::user()->can('create',  [WishlistItem::class, $wishlist]),
                'viewPurchased' => Auth::user()->can('viewPurchased',  $wishlist),
            ],
            'canAddFriend' => !(Auth::user()->isFriends($list->owner())) && Auth::user()->id !== $list->owner()->id
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
