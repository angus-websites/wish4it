<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

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
            'created_at' => $this->created_at,
            'hasCurrentUserReservation' => $this->when($request->user(), function () use ($request) {
                return $this->hasUserReservation($request->user());
            }),
            'can' => $this->when($request->user(), function () use ($request) {
                return $this->can($request->user());
            }),
        ];
    }

    private function hasUserReservation($user): bool
    {
        return WishlistItem::findOrfail($this->id)->hasUserReservation($user);

    }

    private function can($user): array
    {
        $wishlist = Wishlist::findOrfail($this->wishlist_id);
        return [
            'update' => $user->can('update', $wishlist),
            'delete' => $user->can('delete', $wishlist),
            'mark' => $user->can('markAsPurchased', $wishlist),
        ];
    }
}
