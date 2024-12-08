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
    private WishlistService $service;

    public function __construct(WishlistService $service, WishlistItem $resource)
    {
        parent::__construct($resource);

        $this->service = $service;
    }

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
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'hasCurrentUserReservation' => $this->when(isset($this->has_user_reserved), function () use ($request) {
                return $this->has_user_reserved;
            }),
            'linkedShops' => (bool)random_int(0, 1),
            'linkedBrands' => (bool)random_int(0, 1),
        ];
    }

}
