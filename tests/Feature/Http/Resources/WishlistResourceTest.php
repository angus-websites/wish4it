<?php

namespace Feature\Http\Resources;

use App\Http\Resources\WishlistItemResource;
use App\Http\Resources\WishlistResource;
use App\Models\User;
use App\Models\Wishlist;
use Database\Factories\WishlistItemFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\MissingValue;
use Tests\TestCase;

class WishlistResourceTest extends TestCase
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
        $wishlist = Wishlist::factory()->public(true)->create(
            [
                'title' => 'Test Wishlist',
            ]
        );

        $this->user->wishlists()->attach($wishlist, ['role' => 'owner']);
        WishlistItemFactory::new()->count(5)->create(
            [
                'wishlist_id' => $wishlist->id,
            ]
        );


        // Pass to the resource
        $resource = new WishlistResource($wishlist);
        $resource_array = $resource->toArray(request()->setUserResolver(fn () => $this->user));

        // Assert that the structure is correct
        $expected = [
            'id' => $wishlist->id,
            'owner' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
            ],
            'title' => $wishlist->title,
            'public' => true,
            'itemCount' => 5,
            'unpurchasedItemCount' => 5,
        ];

        $this->assertEquals($expected, $resource_array);

    }

}
