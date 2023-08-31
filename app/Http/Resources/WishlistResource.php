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
        ];
    }
}
