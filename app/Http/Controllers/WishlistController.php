<?php

namespace App\Http\Controllers;

use App\Http\Resources\WishlistItemResource;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class WishlistController extends Controller
{
    private WishlistService $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->middleware('auth:sanctum')->except(['show']);
        $this->authorizeResource(Wishlist::class);
        $this->wishlistService = $wishlistService;
    }

    /**
     * Wishlist index page
     */
    public function index(Request $request)
    {

        $user = Auth::user();
        $wishlists = $this->wishlistService->fetchUserWishlists($user);

        return Inertia::render('Wishlist/Index', [
            'lists' => WishlistResource::collection($wishlists),
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
        $this->wishlistService->storeWishlist($data, Auth::user());

        return Redirect::route('wishlists.index')->with('success', 'Wishlist created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wishlist $wishlist)
    {

        $currentUser = Auth::user();
        $currentUserId = $currentUser?->id;
        $userAuthenticated = Auth::check();

        // Eager load and fetch the items
        $itemsCollection = $wishlist->items()->with('reservations')->get();

        // If the user is not logged in or the user is not the owner of the wishlist,
        // remove purchased items by filtering the collection
        if (!$userAuthenticated || ($currentUserId && !$currentUser->can('viewPurchased', $wishlist))) {
            $itemsCollection = $itemsCollection->reject(function ($item) {
                return $item->needs <= $item->has;
            });
        }

        // Manually paginate the filtered collection
        $perPage = 16;
        $page = Paginator::resolveCurrentPage() ?: 1;
        $itemsPaginated = new LengthAwarePaginator(
            $itemsCollection->forPage($page, $perPage),
            $itemsCollection->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        // Create json resources
        $list = new WishlistResource($wishlist);
        $items = WishlistItemResource::collection($itemsPaginated);

        // If the user is not logged in then show the public wishlist page
        if (! $currentUserId) {

            // Store the intended url in the session to redirect after login
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
                'deleteList' => $currentUser->can('delete', $wishlist),
                'editList' => $currentUser->can('update', $wishlist),
                'createItems' => $currentUser->can('create', [WishlistItem::class, $wishlist]),
                'viewPurchased' => $currentUser->can('viewPurchased', $wishlist),
            ],
            'canAddFriend' => $currentUserId && ! $currentUser->isFriends($list->owner()) && $currentUserId !== $list->owner()->id,
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
