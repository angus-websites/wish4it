<?php

namespace App\Http\Controllers;

use App\Http\Resources\WishlistItemResource;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

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
    public function index(Request $request)
    {
        return Inertia::render('Wishlist/Index', [
            'lists' => WishlistResource::collection(Auth::user()->wishlists()->get()),
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
    public function show(Wishlist $wishlist)
    {

        $currentUserId = Auth::id();
        $itemsQuery = $wishlist->items();
        $itemsCollection = $itemsQuery->get();

        // If the user is not logged in or the user is not the owner of the wishlist then remove purchased items
        if (! Auth::check() || ($currentUserId && ! Auth::user()->can('viewPurchased', $wishlist))) {
            $itemsCollection = $itemsCollection->filter(fn ($item) => $item->needs > $item->has);
        }

        // Create a resource collection from the items and paginate
        $items = WishlistItemResource::collection(
            $itemsCollection->isEmpty() ? $itemsQuery->paginate(16)->withQueryString() : $itemsCollection->toQuery()->paginate(16)->withQueryString()
        );

        // Create a resource from the wishlist
        $list = new WishlistResource($wishlist);

        // If the user is not logged in then show the public wishlist page
        if (! $currentUserId) {
            session(['url.intended' => url()->current()]);

            return Inertia::render('Guest/WishlistPublic', [
                'list' => $list,
                'items' => $items,
            ]);
        }

        return Inertia::render('Wishlist/View', [
            'list' => $list,
            'items' => $items,
            'can' => [
                'deleteList' => Auth::user()->can('delete', $wishlist),
                'editList' => Auth::user()->can('update', $wishlist),
                'createItems' => Auth::user()->can('create', [WishlistItem::class, $wishlist]),
                'viewPurchased' => Auth::user()->can('viewPurchased', $wishlist),
            ],
            'canAddFriend' => $currentUserId && ! Auth::user()->isFriends($list->owner()) && $currentUserId !== $list->owner()->id,
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
