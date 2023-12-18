<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Used to fetch and cache the wishlist data
 */
class WishlistService
{
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
    public function fetchAvailableWishlistItems(Wishlist $wishlist): \Illuminate\Database\Query\Builder
    {

        return DB::table('wishlist_items as wi')
            ->leftJoin('reservations as r', 'wi.id', '=', 'r.wishlist_item_id')
            ->select('wi.*', DB::raw('COALESCE(SUM(r.quantity), 0) as has'))
            ->where('wi.wishlist_id', $wishlist->id)
            ->groupBy('wi.id', 'wi.wishlist_id', 'wi.needs')
            ->havingRaw('COALESCE(SUM(r.quantity), 0) < wi.needs');
    }

    /**
     * Fetch a query builder for all the reserved wishlist items
     */
    public function fetchReservedWishlistItems(Wishlist $wishlist): \Illuminate\Database\Query\Builder
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
     * Fetch all the wishlist items for a given wishlist
     */
    public function fetchWishlistItems(Wishlist $wishlist): Collection
    {
        return $wishlist->items()->with('reservations')->get();
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
