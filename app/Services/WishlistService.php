<?php

namespace App\Services;

use App\Http\Resources\WishlistItemResource;
use App\Http\Resources\WishlistResource;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Used to fetch and cache the wishlist data
 */
class WishlistService
{
    private int $paginationLength = 16;

    /**
     * @param string $wishlistId
     * Check a wishlist exists or not
     */
    public function checkWishlistExists(string $wishlistId): bool
    {
        return Wishlist::where('id', $wishlistId)->exists();
    }


    /**
     * @param User $user
     * Fetch all the wishlist for a given user
     */
    public function fetchUserWishlists(User $user): Collection
    {
        return $user->wishlists()->get();
    }

    /**
     * @param string $wishlistId
     * @return Wishlist
     * Fetch a wishlist by its ID
     */
    public function fetchWishlist(string $wishlistId): Wishlist
    {
        return Wishlist::findOrFail($wishlistId);
    }

    /**
     * Fetch a query builder for all the available wishlist items
     */
    public function fetchAvailableWishlistItems(Wishlist $wishlist): Builder
    {

        return DB::table('wishlist_items as wi')
            ->leftJoin('reservations as r', 'wi.id', '=', 'r.wishlist_item_id')
            ->select('wi.*', DB::raw('COALESCE(SUM(r.quantity), 0) as has'))
            ->where('wi.wishlist_id', $wishlist->id)
            ->groupBy('wi.id', 'wi.wishlist_id', 'wi.needs')
            ->havingRaw('COALESCE(SUM(r.quantity), 0) < wi.needs');
    }

    /**
     * Fetch a query builder for all the available wishlist items but with a user
     * so the query builder can be used to check if the user has reserved an item
     */
    public function fetchAvailableWishlistItemsWithUser(Wishlist $wishlist, User $user): Builder
    {
        return DB::table('wishlist_items as wi')
            ->leftJoin('reservations as r', 'wi.id', '=', 'r.wishlist_item_id')
            ->select([
                'wi.*',
                DB::raw('COALESCE(SUM(r.quantity), 0) as has'),
                DB::raw('EXISTS(SELECT 1 FROM reservations WHERE wishlist_item_id = wi.id AND user_id = ?) as has_user_reserved')
            ])
            ->where('wi.wishlist_id', $wishlist->id)
            ->groupBy('wi.id', 'wi.wishlist_id', 'wi.needs')
            ->havingRaw('COALESCE(SUM(r.quantity), 0) < wi.needs')
            ->setBindings([$user->id], 'select');
    }

    /**
     * Fetch a query builder for all the reserved wishlist items
     */
    public function fetchReservedWishlistItems(Wishlist $wishlist): Builder
    {
        return DB::table('wishlist_items as wi')
            ->join('reservations as r', 'wi.id', '=', 'r.wishlist_item_id')
            ->select(
                'wi.*',
                DB::raw('SUM(r.quantity) as has')
            )
            ->where('wi.wishlist_id', $wishlist->id)
            ->groupBy('wi.id', 'wi.wishlist_id', 'wi.needs');
    }


    /**
     * Fetch all the wishlist items for a given wishlist, purchased or not
     */
    public function fetchWishlistItems(Wishlist $wishlist): Builder
    {
        // Fetch ALL the wishlist items
        return DB::table('wishlist_items as wi')
            ->leftJoin('reservations as r', 'wi.id', '=', 'r.wishlist_item_id')
            ->select('wi.*', DB::raw('COALESCE(SUM(r.quantity), 0) as has'))
            ->where('wi.wishlist_id', $wishlist->id)
            ->groupBy('wi.id', 'wi.wishlist_id', 'wi.needs');

    }

    /**
     * Fetch all the wishlist items for a given wishlist as a resource
     */
    public function fetchWishlistItemsResource(Wishlist $wishlist, ?User $user): JsonResource
    {
        if ($user && $user->can('viewPurchased', $wishlist)){
            $data =  $this->fetchWishlistItems($wishlist);
        }
        elseif ($user){
            $data = $this->fetchAvailableWishlistItemsWithUser($wishlist, $user);
        }
        else {
            $data = $this->fetchAvailableWishlistItems($wishlist);
        }

        // Return as a resource
        return WishlistItemResource::collection($data->paginate($this->paginationLength));
    }

    /**
     * Fetch the wishlist itself as a resource
     */
    public function fetchWishlistResource(Wishlist $wishlist): WishlistResource
    {
        return new WishlistResource($wishlist);
    }

    /**
     * Store a wishlist in the database
     */
    public function storeWishlist(array $data, User $user, $role="owner"): Wishlist
    {
        // Create a new wishlist
        $wishlist = Wishlist::create($data);

        // Attach this user to the wishlist as an owner
        $user->wishlists()->attach($wishlist->id, ['role' => $role]);

        return $wishlist;

    }

    /**
     * Store a new wishlist item in the database
     */
    public function storeWishlistItem(Wishlist $wishlist, array $data): WishlistItem
    {
        return $wishlist->items()->create($data);
    }

    /**
     * @param Wishlist $wishlist
     * @param array $data
     * @return Wishlist
     *  Update a wishlist in the database
     */
    public function updateWishlist(Wishlist $wishlist, array $data): Wishlist
    {
        $wishlist->update($data);
        return $wishlist;
    }

    /**
     * @param Wishlist $wishlist
     * Delete a wishlist from the database
     */
    public function deleteWishlist(Wishlist $wishlist): void
    {
        $wishlist->delete();
    }


}
