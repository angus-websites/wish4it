<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $owner = $this->owner();
        $user = $request->user();

        return [
            'id' => $this->id,
            'owner' => [
                'id' => $owner->id,
                'name' => $owner->name,
                'username' => $owner->username,
            ],
            'title' => $this->title,
            'public' => boolval($this->public),
            'itemCount' => $this->items()->count(),
            'unpurchasedItemCount' => $this->getUnpurchasedCount(),
            'can' => $this->when($request->user(), function () use ($user) {
                return [
                    'update' => $user->can('update', $this->resource),
                    'delete' => $user->can('delete', $this->resource),
                    'mark' => $user->can('markAsPurchased', $this->resource),
                ];
            }),
        ];
    }
}
