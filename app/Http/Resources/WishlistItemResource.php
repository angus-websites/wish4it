<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Services\WishlistService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistItemResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        $service = new WishlistService();

        // Fetch the wishlist item as an object (model instance)
        $wishlist = Wishlist::find($this->wishlist_id)->makeHidden(['created_at', 'updated_at']);

        // Convert to an array and wrap it in a collection
        $wishlistCollection = collect($wishlist->items);

        // Fetch the linked items info
        $linkedInfo = $service->getSpecificLinkedItemInfo($wishlistCollection, $this->id);

        // First check we have a user in the request
        if ($request->user()) {

            // Check if the current user is owner, if so, hide the linked info
            if ($wishlist->users()->where('id', $request->user()->id)->exists()) {
                $linkedInfo = [];
            }
        }


        return [
            'id' => $this->id,
            'wishlist_id' => $this->wishlist_id,
            'name' => $this->name,
            'brand' => $this->brand,
            'price' => $this->price,
            'url' => $this->url,
            'comment' => $this->comment,
            'image' => $this->image,
            'needs' => $this->needs,
            'has' => $this->has,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'hasCurrentUserReservation' => $this->when(isset($this->has_user_reserved), function () use ($request) {
                return $this->has_user_reserved;
            }),
            'linkedShops' => $linkedInfo["linkedShops"] ?? false,
            'linkedBrands' => $linkedInfo["linkedBrands"] ?? false,
        ];
    }

}
