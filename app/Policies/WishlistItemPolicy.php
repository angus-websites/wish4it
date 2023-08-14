<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Auth\Access\Response;


class WishlistItemPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Wishlist $wishlist) 
    {
        return $user->id === $wishlist->owner()->id
            ? Response::allow()
            : Response::deny('You cannot create new items in this wishlist');
    }
}
