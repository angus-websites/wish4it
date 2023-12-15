<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class WishlistService
{
    /**
     * Used to fetch and cache the wishlist data
     */


    /**
     * @param User $user
     * Fetch the wishlist for a given user
     */
    public function fetchUserWishlists(User $user): Collection
    {
        return $user->wishlists()->get();
    }

    /**
     * @param User $user
     * Fetch a particular wishlist for a given user
     */
    public function fetchWishlist(string $wishlistId): Wishlist
    {
        return Wishlist::where('id', $wishlistId)->firstOrFail();
    }


}
