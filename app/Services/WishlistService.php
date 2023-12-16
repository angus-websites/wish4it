<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
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
    public function wishlistExists(string $wishlistId): bool
    {
        return Wishlist::where('id', $wishlistId)->exists();
    }


    /**
     * @param User $user
     * Fetch the wishlist for a given user
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
     * Store a wishlist in the database
     */
    public function storeWishlist(array $data): Wishlist
    {
        return Wishlist::create($data);
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
