<?php

namespace Tests\Feature\Http\Resources;

use App\Models\User;
use App\Models\Wishlist;
use Database\Factories\WishlistItemFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistItemResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user with a wishlist and 5 items
        $this->user = User::factory()->create();
        $this->public_wishlist = Wishlist::factory()->hasItems(5)->public(true)->create();
        $this->user->wishlists()->attach($this->public_wishlist, ['role' => 'owner']);
        $this->private_wishlist = Wishlist::factory()->hasItems(5)->public(false)->create();
        $this->user->wishlists()->attach($this->private_wishlist, ['role' => 'owner']);
    }


    public function test_wishlist_item_resource()
    {
        // Create a wishlist
        $wishlist = Wishlist::factory()->create();
        $this->user->wishlists()->attach($wishlist, ['role' => 'owner']);
        $wishlist_item = WishlistItemFactory::new()->create(
            [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'ipad',
                'brand' => 'apple',
                'price' => 1000,
                'url' => 'https://www.apple.com/ipad',
                'comment' => 'I want the 12.9" version',
            ]
        );

        // Pass to the resource
        $resource = new \App\Http\Resources\WishlistItemResource($wishlist_item);

        // Assert that the resource has the correct data
        $this->assertEquals(
            [
                'id' => $wishlist_item->id,
                'wishlist_id' => $wishlist_item->wishlist_id,
                'needs' => $wishlist_item->needs,
                'name' => $wishlist_item->name,
                'brand' => $wishlist_item->brand,
                'price' => $wishlist_item->price,
                'url' => $wishlist_item->url,
                'comment' => $wishlist_item->comments,
                'has' => 0,
                'created_at' => $wishlist_item->created_at,
                'updated_at' => $wishlist_item->updated_at,
            ],
            $resource->toArray(request())
        );


    }
}
