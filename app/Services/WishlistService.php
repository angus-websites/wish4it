<?php

namespace App\Services;

use App\Enums\MarkAsPurchasedStatusEnum;
use App\Http\Resources\WishlistItemResource;
use App\Http\Resources\WishlistResource;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


/**
 * Used to fetch and cache the wishlist data
 */
class WishlistService
{
    private int $paginationLength = 16;

    private function invalidateCache($wishlist_id): void
    {
        Cache::tags("wishlist_{$wishlist_id}")->flush();
    }

    /**
     * @param string $wishlistId
     * @return bool
     * Check a wishlist exists or not
     */
    public function checkWishlistExists(string $wishlistId): bool
    {
        return Wishlist::where('id', $wishlistId)->exists();
    }


    /**
     * @param User $user
     * @return Collection
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
     * @param Wishlist $wishlist
     * @return Builder
     */
    public function fetchAvailableWishlistItems(Wishlist $wishlist): Builder
    {

        return DB::table('wishlist_items as wi')
            ->leftJoin('reservations as r', 'wi.id', '=', 'r.wishlist_item_id')
            ->select('wi.*', DB::raw('COALESCE(SUM(r.quantity), 0) as has'))
            ->where('wi.wishlist_id', $wishlist->id)
            ->groupBy('wi.id', 'wi.wishlist_id', 'wi.needs')
            ->havingRaw('COALESCE(SUM(r.quantity), 0) < wi.needs')
            ->orderBy('wi.created_at', 'desc');
    }

    /**
     * Fetch a query builder for all the available wishlist items but with a user
     * so the query builder can be used to check if the user has reserved an item
     * @param Wishlist $wishlist
     * @param User $user
     * @return Builder
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
            ->setBindings([$user->id], 'select')
            ->orderBy('wi.created_at', 'desc');
    }

    /**
     * Fetch a query builder for all the reserved wishlist items
     * @param Wishlist $wishlist
     * @return Builder
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
            ->groupBy('wi.id', 'wi.wishlist_id', 'wi.needs')
            ->orderBy('wi.created_at', 'desc');

    }


    /**
     * Fetch all the wishlist items for a given wishlist, purchased or not
     * @param Wishlist $wishlist
     * @return Builder
     */
    public function fetchWishlistItems(Wishlist $wishlist): Builder
    {
        // Fetch ALL the wishlist items
        return DB::table('wishlist_items as wi')
            ->leftJoin('reservations as r', 'wi.id', '=', 'r.wishlist_item_id')
            ->select('wi.*', DB::raw('COALESCE(SUM(r.quantity), 0) as has'))
            ->where('wi.wishlist_id', $wishlist->id)
            ->groupBy('wi.id', 'wi.wishlist_id', 'wi.needs')
            ->orderBy('wi.created_at', 'desc');


    }

    /**
     * Fetch all the wishlist items for a given wishlist as a resource
     * @param Wishlist $wishlist
     * @param User|null $user
     * @param int $page
     * @return JsonResource
     */
    public function fetchWishlistItemsResource(Wishlist $wishlist, ?User $user, int $page = 1): JsonResource
    {
        // Determine cache key based on permissions and page
        $permissionKey = $user && $user->can('viewPurchased', $wishlist) ? 'with_purchased' : 'without_purchased';
        $cacheKey = "wishlist_{$wishlist->id}_{$permissionKey}_page_{$page}";

        // Check if data is in cache
        $cachedData = Cache::tags("wishlist_{$wishlist->id}")->get($cacheKey);

        if (!$cachedData) {
            if ($user && $user->can('viewPurchased', $wishlist)) {
                $data = WishlistItemResource::collection($this->fetchWishlistItems($wishlist)->paginate($this->paginationLength, ['*'], 'page', $page));
            } elseif ($user) {
                $data = WishlistItemResource::collection($this->fetchAvailableWishlistItemsWithUser($wishlist, $user)->paginate($this->paginationLength, ['*'], 'page', $page));
            } else {
                $data = WishlistItemResource::collection($this->fetchAvailableWishlistItems($wishlist)->paginate($this->paginationLength, ['*'], 'page', $page));
            }

            // Store in cache indefinitely
            Cache::tags("wishlist_{$wishlist->id}")->put($cacheKey, $data);
            $cachedData = $data;
        }

        // Return cached data
        return $cachedData;
    }

    /**
     * Fetch the wishlist itself as a resource
     * @param Wishlist $wishlist
     * @return WishlistResource
     */
    public function fetchWishlistResource(Wishlist $wishlist): WishlistResource
    {
        return new WishlistResource($wishlist);
    }

    /**
     * Store a wishlist in the database
     * @param array $data
     * @param User $user
     * @param string $role
     * @return Wishlist
     */
    public function storeWishlist(array $data, User $user, string $role="owner"): Wishlist
    {
        // Create a new wishlist
        $wishlist = Wishlist::create($data);

        // Attach this user to the wishlist as an owner
        $user->wishlists()->attach($wishlist->id, ['role' => $role]);

        return $wishlist;

    }

    /**
     * Store a new wishlist item in the database
     * @param Wishlist $wishlist
     * @param array $data
     * @return WishlistItem
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

        // Invalidate cache
        $this->invalidateCache($wishlist->id);

        return $wishlist;
    }

    /**
     * Update a wishlist item in the database
     * @param WishlistItem $item
     * @param array $data
     * @return WishlistItem
     */
    public function updateWishlistItem(WishlistItem $item, array $data): WishlistItem
    {
        $item->update($data);

        // Invalidate cache
        $this->invalidateCache($item->wishlist_id);

        return $item;
    }

    /**
     * @param Wishlist $wishlist
     * Delete a wishlist from the database
     */
    public function deleteWishlist(Wishlist $wishlist): void
    {
        $wishlist->delete();

        // Invalidate cache
        $this->invalidateCache($wishlist->id);

    }

    /**
     * @param WishlistItem $item
     * Destroy a wishlist item in the database
     */
    public function deleteWishlistItem(WishlistItem $item): void
    {
        $item->delete();

        // Invalidate cache
        $this->invalidateCache($item->wishlist_id);
    }

    /**
     * @param User $user - The user who is marking this item as purchased
     * @param WishlistItem $item
     * @param int $quantity - How many the user would like to mark as purchased
     * @param int $has - How many of this item has been purchased according to the frontend
     * Mark a wishlist item as purchased
     * @return MarkAsPurchasedStatusEnum
     */
    public function markAsPurchased(User $user, WishlistItem $item, int $quantity, int $has=0): MarkAsPurchasedStatusEnum
    {
        $clientHas = $has;
        $serverHas = $item->has;

        if ($clientHas !== $serverHas)
        {
            // If the item is now marked as purchased, return an error
            if ($serverHas >= $item->needs)
            {
                return MarkAsPurchasedStatusEnum::ALREADY_PURCHASED;
            }

            // Return an error
            return MarkAsPurchasedStatusEnum::HAS_CHANGED;
        }

        // Create the new reservation
        $reservation = new Reservation();
        $reservation->wishlist_item_id = $item->id;
        $reservation->quantity = $quantity;
        $reservation->user_id = $user->id;
        $reservation->save();

        // Invalidate cache
        $this->invalidateCache($item->wishlist_id);

        // Return success
        return MarkAsPurchasedStatusEnum::SUCCESS;
    }

}
