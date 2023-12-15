<?php

namespace App\Services;

use App\Models\User;
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
    public function fetchWishlistForUser(User $user): Collection
    {
        return $user->wishlists()->get();
    }

}
