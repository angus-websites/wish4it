<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Auth\Access\Response;

class WishlistPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can mark any items
     * as purchased or not
     */
    public function markAsPurchased(User $user, Wishlist $wishlist)
    {
        return $user->isFriends($wishlist->user())
            ? Response::allow()
            : Response::deny('You cannot mark this item as purchased');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Wishlist $wishlist)
    {

        // If the wishlist is public then anybody can view this list (even without an account)
        if ($wishlist->isPublic()){
            return Response::allow();
        }
        
        if ($user){
            // Only owners can view their own private lists
            return $user->id === $wishlist->user_id
                ? Response::allow()
                : Response::deny('You cannot view this wishlist');
        }

        return Response::deny('You cannot view this wishlist')
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wishlist $wishlist)
    {        
        return $user->id === $wishlist->user_id
            ? Response::allow()
            : Response::deny('You cannot update this wishlist');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wishlist $wishlist)
    {
        return $user->id === $wishlist->user_id
            ? Response::allow()
            : Response::deny('You cannot delete this wishlist');
    }

}
