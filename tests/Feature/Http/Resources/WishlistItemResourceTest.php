<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\WishlistItemResource;
use App\Models\User;
use App\Models\Wishlist;
use Database\Factories\WishlistItemFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Carbon;
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


    public function test_wishlist_item_resource_as_guest()
    {
        // Mock current datetime now
        Carbon::setTestNow(
            Carbon::create(2021, 1, 1, 12, 0, 0)
        );

        // Create a wishlist
        $wishlist = Wishlist::factory()->public(true)->create();
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
        $resource = new WishlistItemResource($wishlist_item);
        $resource_array = $resource->toArray(request());

        // Assert that the structure is correct
        $expected = [
            'id' => $wishlist_item->id,
            'wishlist_id' => $wishlist_item->wishlist_id,
            'needs' => $wishlist_item->needs,
            'name' => $wishlist_item->name,
            'brand' => $wishlist_item->brand,
            'price' => $wishlist_item->price,
            'url' => $wishlist_item->url,
            'comment' => $wishlist_item->comment,
            'has' => 0,
            'created_at' => '2021-01-01',
            'image' => null,
            'hasCurrentUserReservation' => new MissingValue,
        ];

        $this->assertEquals($expected, $resource_array);

    }

    /**
     * Test with a user
     */
    public function test_wishlist_item_resource_with_user()
    {

        // Mock current datetime now
        Carbon::setTestNow(
            Carbon::create(2021, 1, 1, 12, 0, 0)
        );

        // Create a wishlist
        $wishlist = Wishlist::factory()->public(true)->create();
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
        $resource = new WishlistItemResource($wishlist_item);
        $resource_array = $resource->toArray(request()->setUserResolver(fn () => $this->user));

        // Assert that the structure is correct
        $expected = [
            'id' => $wishlist_item->id,
            'wishlist_id' => $wishlist_item->wishlist_id,
            'needs' => $wishlist_item->needs,
            'name' => $wishlist_item->name,
            'brand' => $wishlist_item->brand,
            'price' => $wishlist_item->price,
            'url' => $wishlist_item->url,
            'comment' => $wishlist_item->comment,
            'has' => 0,
            'created_at' => '2021-01-01',
            'image' => null,
            'hasCurrentUserReservation' => new MissingValue,
        ];

        $this->assertEquals($expected, $resource_array);
    }


}
