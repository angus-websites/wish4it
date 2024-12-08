<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\WishlistItemResource;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Services\WishlistService;
use Database\Factories\UserFactory;
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

        $this->guest = User::factory()->create();
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
            'linkedShops' => false,
            'linkedBrands' => false
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
            'linkedShops' => false,
            'linkedBrands' => false
        ];

        $this->assertEquals($expected, $resource_array);
    }

    /**
     * Test Linked Brands
     * These are items on the same wishlist with the same brand
     */
    public function test_wishlist_item_resource_with_linked_brands()
    {
        // Mock current datetime now
        Carbon::setTestNow(
            Carbon::create(2021, 1, 1, 12, 0, 0)
        );

        // Create a wishlist
        $wishlist = Wishlist::factory()->public(true)->forUser($this->user)->create();

        // Create two items using the factory with the same brand
        $ipad = WishlistItem::factory()->create(
             [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'ipad',
                'brand' => 'apple',

             ]
        );

        WishlistItem::factory()->create(
             [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'mac',
                'brand' => 'apple',

             ]
        );

        // Pass to the resource
        $resource = new WishlistItemResource($ipad);

        $resource_array = $resource->toArray(request()->setUserResolver(fn () => $this->guest));

        // Assert that the structure is correct
        $expected = [
            'id' => $ipad->id,
            'wishlist_id' => $ipad->wishlist_id,
            'needs' => $ipad->needs,
            'name' => $ipad->name,
            'brand' => $ipad->brand,
            'price' => $ipad->price,
            'url' => $ipad->url,
            'comment' => $ipad->comment,
            'has' => 0,
            'created_at' => '2021-01-01',
            'image' => null,
            'hasCurrentUserReservation' => new MissingValue,
            'linkedShops' => false,
            'linkedBrands' => true
        ];

        $this->assertEquals($expected, $resource_array);


    }

    /**
     * Test linked shops
     * These are items on the same wishlist that are from the same URL (shop)
     */
    public function test_wishlist_item_resource_with_linked_shops()
    {
        // Mock current datetime now
        Carbon::setTestNow(
            Carbon::create(2021, 1, 1, 12, 0, 0)
        );

        // Create a wishlist
        $wishlist = Wishlist::factory()->public(true)->forUser($this->user)->create();

        // Create two items using the factory with the same brand
        $oil = WishlistItem::factory()->create(
             [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'Olive oil',
                'brand' => 'Filippo Berio',
                'url' => 'https://www.amazon.com/products/123',

             ]
        );

        WishlistItem::factory()->create(
             [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'Charger',
                'brand' => 'Anker',
                'url' => 'https://www.amazon.com/products/456',

             ]
        );

        // Pass to the resource
        $resource = new WishlistItemResource($oil);

        $resource_array = $resource->toArray(request()->setUserResolver(fn () => $this->guest));

        // Assert that the structure is correct
        $expected = [
            'id' => $oil->id,
            'wishlist_id' => $oil->wishlist_id,
            'needs' => $oil->needs,
            'name' => $oil->name,
            'brand' => $oil->brand,
            'price' => $oil->price,
            'url' => $oil->url,
            'comment' => $oil->comment,
            'has' => 0,
            'created_at' => '2021-01-01',
            'image' => null,
            'hasCurrentUserReservation' => new MissingValue,
            'linkedShops' => true,
            'linkedBrands' => false
        ];

        $this->assertEquals($expected, $resource_array);

    }

    public function test_linked_info_shouldnt_appear_for_wishlist_owner()
    {
        // Mock current datetime now
        Carbon::setTestNow(
            Carbon::create(2021, 1, 1, 12, 0, 0)
        );

        // Create a wishlist
        $wishlist = Wishlist::factory()->public(true)->forUser($this->user)->create();

        // Create two items using the factory with the same brand
        $oil = WishlistItem::factory()->create(
             [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'Olive oil',
                'brand' => 'Filippo Berio',
                'url' => 'https://www.amazon.com/products/123',

             ]
        );

        WishlistItem::factory()->create(
             [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'Charger',
                'brand' => 'Anker',
                'url' => 'https://www.amazon.com/products/456',

             ]
        );

        // Pass to the resource
        $resource = new WishlistItemResource($oil);

        $resource_array = $resource->toArray(request()->setUserResolver(fn () => $this->user));

        // Assert that the structure is correct
        $expected = [
            'id' => $oil->id,
            'wishlist_id' => $oil->wishlist_id,
            'needs' => $oil->needs,
            'name' => $oil->name,
            'brand' => $oil->brand,
            'price' => $oil->price,
            'url' => $oil->url,
            'comment' => $oil->comment,
            'has' => 0,
            'created_at' => '2021-01-01',
            'image' => null,
            'hasCurrentUserReservation' => new MissingValue,
            'linkedShops' => false,
            'linkedBrands' => false
        ];

        $this->assertEquals($expected, $resource_array);
    }

    /**
     * Test that linked information is only fetched for
     * items that have not been reserved / purchased
     */
    public function test_linked_info_shouldnt_appear_for_reserved_items()
    {
        // Mock current datetime now
        Carbon::setTestNow(
            Carbon::create(2021, 1, 1, 12, 0, 0)
        );

        // Create a wishlist
        $wishlist = Wishlist::factory()->public(true)->forUser($this->user)->create();

        // Create two items using the factory with the same brand
        $oil = WishlistItem::factory()->create(
             [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'Olive oil',
                'brand' => 'Filippo Berio',
                'url' => 'https://www.amazon.com/products/123',

             ]
        );

        $charger = WishlistItem::factory()->create(
             [
                'wishlist_id' => $wishlist->id,
                'needs' => 1,
                'name' => 'Charger',
                'brand' => 'Anker',
                'url' => 'https://www.amazon.com/products/456',

             ]
        );

        // Mark the charger as reserved
        \DB::table('reservations')->insert([
            'user_id' => $this->guest->id,
            'wishlist_item_id' => $charger->id,
            'quantity' => 1,
        ]);

        // Pass to the resource
        $resource = new WishlistItemResource($oil);

        $resource_array = $resource->toArray(request()->setUserResolver(fn () => $this->guest));

        // Assert that the structure is correct
        $expected = [
            'id' => $oil->id,
            'wishlist_id' => $oil->wishlist_id,
            'needs' => $oil->needs,
            'name' => $oil->name,
            'brand' => $oil->brand,
            'price' => $oil->price,
            'url' => $oil->url,
            'comment' => $oil->comment,
            'has' => 0,
            'created_at' => '2021-01-01',
            'image' => null,
            'hasCurrentUserReservation' => new MissingValue,
            'linkedShops' => false,
            'linkedBrands' => false
        ];

        $this->assertEquals($expected, $resource_array);

    }



}
