<?php

namespace Feature\Services;

use App\Models\User;
use App\Models\Wishlist;
use App\Services\WishlistService;
use Database\Factories\WishlistItemFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistServiceTest extends TestCase
{
    use RefreshDatabase;
    protected WishlistService $wishlistService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wishlistService = new WishlistService();

        // Create a user with a wishlist and 5 items
        $this->user = User::factory()->create();
        $this->public_wishlist = Wishlist::factory()->hasItems(5)->public(true)->create();
        $this->user->wishlists()->attach($this->public_wishlist, ['role' => 'owner']);
        $this->private_wishlist = Wishlist::factory()->hasItems(5)->public(false)->create();
        $this->user->wishlists()->attach($this->private_wishlist, ['role' => 'owner']);
    }

    /**
     * Test that the service can fetch a user's wishlist
     */
    public function test_fetches_valid_wishlist()
    {
        // Fetch the first wishlist
        $wishlist = $this->public_wishlist;

        // Call the service
        $fetched_wishlist = $this->wishlistService->fetchWishlist($wishlist->id);

        // Assert that the wishlist are the same
        $this->assertEquals($wishlist->id, $fetched_wishlist->id);

    }

    /**
     * Test that fetching a wishlist that doesn't exist throws an exception
     */
    public function test_fetches_invalid_wishlist()
    {
        // Assert that the wishlist are the same
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->wishlistService->fetchWishlist('invalid-id');

    }

    /**
     * Test the service can fetch all of a user's wishlists
     */
    public function test_fetches_all_user_wishlists()
    {
        // Call the service
        $fetched_wishlists = $this->wishlistService->fetchUserWishlists($this->user);

        // Assert that the wishlist are the same
        $this->assertEquals($this->user->wishlists()->count(), $fetched_wishlists->count());
    }

    /**
     * Test the service can check if a wishlist exists
     */
    public function test_wishlist_exists()
    {
        // Fetch the first wishlist
        $wishlist = $this->public_wishlist;

        // Call the service
        $wishlist_exists = $this->wishlistService->checkWishlistExists($wishlist->id);

        // Assert that the wishlist are the same
        $this->assertTrue($wishlist_exists);
        ;
    }

    /**
     * Test the service can check if a wishlist doesn't exist
     */
    public function test_wishlist_doesnt_exist()
    {
        // Call the service
        $wishlist_exists = $this->wishlistService->checkWishlistExists('invalid-id');

        // Assert that the wishlist are the same
        $this->assertFalse($wishlist_exists);

    }

    /**
     * Test creating a wishlist
     */
    public function test_store_wishlist()
    {
        // Create a wishlist
        $this->wishlistService->storeWishlist([
            'title' => 'Test Wishlist',
            'public' => true,
        ], $this->user);

        // Assert it exists in the database
        $this->assertDatabaseHas('wishlists', [
            'title' => 'Test Wishlist',
            'public' => true,
        ]);

        $this->assertDatabaseHas('user_wishlist', [
            'user_id' => $this->user->id,
            'role' => 'owner',
        ]);
    }

    /**
     * Test storing a wishlist item
     */
    public function test_store_wishlist_item()
    {
        // Fetch the first wishlist
        $wishlist = $this->public_wishlist;

        // Create a wishlist item
        $this->wishlistService->storeWishlistItem($wishlist, [
            'name' => 'Test Wishlist Item',
            'needs' => 1,
        ]);

        // Assert it exists in the database
        $this->assertDatabaseHas('wishlist_items', [
            'name' => 'Test Wishlist Item',
            'needs' => 1,
        ]);
    }

    /**
     * Test updating a wishlist
     */
    public function test_update_wishlist()
    {
        // Fetch the first wishlist
        $wishlist = $this->public_wishlist;

        // Update the wishlist
        $this->wishlistService->updateWishlist($wishlist, [
            'title' => 'Updated Wishlist',
            'public' => false,
        ]);

        // Assert it exists in the database
        $this->assertDatabaseHas('wishlists', [
            'title' => 'Updated Wishlist',
            'public' => false,
            'id' => $wishlist->id,
        ]);
    }

    /**
     * Test updating a wishlist item
     */
    public function test_update_wishlist_item()
    {
        // Fetch the first wishlist
        $wishlist = $this->public_wishlist;

        // Fetch the first wishlist item
        $item = $wishlist->items()->first();

        // Update the wishlist item
        $this->wishlistService->updateWishlistItem($item, [
            'name' => 'Updated Wishlist Item',
            'needs' => 2,
        ]);

        // Assert it exists in the database
        $this->assertDatabaseHas('wishlist_items', [
            'name' => 'Updated Wishlist Item',
            'needs' => 2,
            'id' => $item->id,
        ]);
    }

    /**
     * Test deleting a wishlist
     */
    public function test_delete_wishlist()
    {
        // Fetch the first wishlist
        $wishlist = $this->public_wishlist;

        // Delete the wishlist
        $this->wishlistService->deleteWishlist($wishlist);

        // Assert it exists in the database
        $this->assertDatabaseMissing('wishlists', [
            'id' => $wishlist->id,
        ]);
    }

    /*
     * Test deleting a wishlist item
     */
    public function test_delete_wishlist_item()
    {
        // Fetch the first wishlist
        $wishlist = $this->public_wishlist;

        // Fetch the first wishlist item
        $item = $wishlist->items()->first();

        // Delete the wishlist item
        $this->wishlistService->deleteWishlistItem($item);

        // Assert it exists in the database
        $this->assertDatabaseMissing('wishlist_items', [
            'id' => $item->id,
        ]);
    }

    /**
     * Test fetching available wishlist items
     */
    public function test_fetch_available_wishlist_items()
    {
        // Create a wishlist
        $wishlist = Wishlist::factory()->create();
        $this->user->wishlists()->attach($wishlist, ['role' => 'owner']);

        // Create 1 manually
        $ipad = WishlistItemFactory::new()->create([
            'wishlist_id' => $wishlist->id,
            'name' => 'iPad',
            'needs' => 1,
        ]);

        // Create 3 other items
        $others = WishlistItemFactory::new()->count(3)->create([
            'wishlist_id' => $wishlist->id,
        ]);


        // Mark the ipad as reserved
        \DB::table('reservations')->insert([
            'user_id' => $this->user->id,
            'wishlist_item_id' => $ipad->id,
            'quantity' => 1,
        ]);


        // Fetch the available items
        $available_items = $this->wishlistService->fetchAvailableWishlistItems($wishlist)->get();

        // Convert the id's to strings in a collection
        $others = $others->map(function ($item) {
            return $item->id->toString();
        });

        $available_items = $available_items->map(function ($item) {
            return $item->id;
        });

        // Assert that the available items are the same as the others
        $this->assertEqualsCanonicalizing($others, $available_items);

    }

    /**
     * Test fetching available wishlist items with a user
     */
    public function test_fetch_available_wishlist_items_with_user()
    {
        // Create a wishlist
        $wishlist = Wishlist::factory()->create();
        $this->user->wishlists()->attach($wishlist, ['role' => 'owner']);

        // Create 1 manually
        $ipad = WishlistItemFactory::new()->create([
            'wishlist_id' => $wishlist->id,
            'name' => 'iPad',
            'needs' => 10,
        ]);

        // Create 1 manually
        $dog = WishlistItemFactory::new()->create([
            'wishlist_id' => $wishlist->id,
            'name' => 'dog',
            'needs' => 1,
        ]);

        // Mark the ipad as reserved (by the user)
        \DB::table('reservations')->insert([
            'user_id' => $this->user->id,
            'wishlist_item_id' => $ipad->id,
            'quantity' => 1,
        ]);

        // Mark the dog as reserved (by another user)
        \DB::table('reservations')->insert([
            'user_id' => User::factory()->create()->id,
            'wishlist_item_id' => $dog->id,
            'quantity' => 1,
        ]);



        // Fetch the available items
        $available_items = $this->wishlistService->fetchAvailableWishlistItemsWithUser($wishlist, $this->user)->get();

        $available_ids = $available_items->map(function ($item) {
            return $item->id;
        })->toArray();

        $others = [$ipad->id->toString()];

        // Assert that the available items are the same as the others
        $this->assertEqualsCanonicalizing($others, $available_ids);

        // Assert the has_user_reserved is true for the ipad
        $this->assertTrue((bool) $available_items->first()->has_user_reserved);


    }


    /**
     * Test fetching all reserved wishlist items
     */
    public function test_fetch_reserved_wishlist_items()
    {
        // Create a wishlist
        $wishlist = Wishlist::factory()->create();
        $this->user->wishlists()->attach($wishlist, ['role' => 'owner']);

        // Create 1 manually
        $ipad = WishlistItemFactory::new()->create([
            'wishlist_id' => $wishlist->id,
            'name' => 'iPad',
            'needs' => 1,
        ]);

        $dog = WishlistItemFactory::new()->create([
            'wishlist_id' => $wishlist->id,
            'name' => 'dog',
            'needs' => 1,
        ]);

        // Create 3 other items
        $others = WishlistItemFactory::new()->count(3)->create([
            'wishlist_id' => $wishlist->id,
        ]);


        // Mark the ipad as reserved
        \DB::table('reservations')->insert([
            'user_id' => $this->user->id,
            'wishlist_item_id' => $ipad->id,
            'quantity' => 1,
        ]);

        // Mark the dog as reserved
        \DB::table('reservations')->insert([
            'user_id' => $this->user->id,
            'wishlist_item_id' => $dog->id,
            'quantity' => 1,
        ]);


        // Fetch the available items
        $reserved_items = $this->wishlistService->fetchReservedWishlistItems($wishlist)->get();

        // Check the reserved items equals the ipad and dog
        $this->assertEqualsCanonicalizing([$ipad->id, $dog->id], $reserved_items->pluck('id')->toArray());
    }

    /**
     * Test fetching all wishlist items
     */
    public function test_fetch_wishlist_items()
    {
        // Use the user's wishlist
        $wishlist = $this->public_wishlist;

        // Fetch the items
        $items = $this->wishlistService->fetchWishlistItems($wishlist);

        // Assert that the items are the same
        $this->assertEquals($wishlist->items()->count(), $items->get()->count());
    }

    /**
     * Test marking an item as purchased
     */
    public function test_mark_as_purchased()
    {
        // Use the user's wishlist
        $wishlist = $this->public_wishlist;

        // Create a new item
        $item = WishlistItemFactory::new()->create([
            'wishlist_id' => $wishlist->id,
            'name' => 'iPad',
            'needs' => 1,
        ]);

        // New user
        $user = User::factory()->create();

        // Mark the item as purchased
        $this->wishlistService->markAsPurchased($user, $item, 1, 0);


        // Assert that the reservation has been created
        $this->assertDatabaseHas('reservations', [
            'wishlist_item_id' => $item->id,
            'quantity' => 1,
            'user_id' => $user->id,
        ]);
    }

    /*
     * Test marking an item as purchased that has already been purchased
     */
    public function test_mark_as_purchased_already_purchased()
    {
        // Use the user's wishlist
        $wishlist = $this->public_wishlist;

        // Create a new item
        $item = WishlistItemFactory::new()->create([
            'wishlist_id' => $wishlist->id,
            'name' => 'iPad',
            'needs' => 1,
        ]);

        // New user
        $user1 = User::factory()->create();

        // Manually create a reservation
        \DB::table('reservations')->insert([
            'wishlist_item_id' => $item->id,
            'quantity' => 1,
            'user_id' => $user1->id,
        ]);

        // Create another user
        $user2 = User::factory()->create();


        // Mark the item as purchased
        $response = $this->wishlistService->markAsPurchased($user2, $item, 1, 0);

        // Assert the enum is correct
        $this->assertEquals(\App\Enums\MarkAsPurchasedStatusEnum::ALREADY_PURCHASED, $response);
    }

    public function test_get_linked_items_info()
    {

        // Create an example collection of items

        $items = collect([
            (object) [
                'id' => 1,
                'name' => 'Monitor',
                'url' => 'https://www.argos.co.uk/product/3080618',
                'brand' => "Asus",
            ],
            (object) [
                'id' => 2,
                'name' => 'Highland Cow',
                'url' => 'https://www.argos.co.uk/product/6793878',
                'brand' => "Jellycat",
            ],
            (object) [
                'id' => 3,
                'name' => 'T shirt',
                'url' => 'https://downthelinesurf.co.uk/products/123',
                'brand' => "Patagonia",
            ],
            (object) [
                'id' => 4,
                'name' => 'Sleeping bag',
                'url' => 'https://eu.patagonia.com/products/sleeping-bag',
                'brand' => "Patagonia",
            ],
        ]);

        $expected = collect([
            [
                'id' => 1,
                'linkedShops' => true,
                'linkedBrands' => false,

            ],
            [
                'id' => 2,
                'linkedShops' => true,
                'linkedBrands' => false,
            ],
            [
                'id' => 3,
                'linkedShops' => false,
                'linkedBrands' => true,
            ],
            [
                'id' => 4,
                'linkedShops' => false,
                'linkedBrands' => true,
            ],
        ]);

        $result = $this->wishlistService->getLinkedItemsInfo($items);

        $this->assertEquals($expected, $result);

    }

    public function test_get_specific_linked_item_info()
    {

        // Create an example collection of items

        $items = collect([
            (object) [
                'id' => 1,
                'name' => 'Monitor',
                'url' => 'https://www.argos.co.uk/product/3080618',
                'brand' => "Asus",
            ],
            (object) [
                'id' => 2,
                'name' => 'Highland Cow',
                'url' => 'https://www.argos.co.uk/product/6793878',
                'brand' => "Jellycat",
            ],
            (object) [
                'id' => 3,
                'name' => 'T shirt',
                'url' => 'https://downthelinesurf.co.uk/products/123',
                'brand' => "Patagonia",
            ],
            (object) [
                'id' => 4,
                'name' => 'Sleeping bag',
                'url' => 'https://eu.patagonia.com/products/sleeping-bag',
                'brand' => "Patagonia",
            ],
        ]);

        $expected = collect([
            [
                'id' => 3,
                'linkedShops' => false,
                'linkedBrands' => true,
            ],
        ]);

        // We want just the Patagonia t shirt information
        $result = $this->wishlistService->getSpecificLinkedItemInfo($items, 3);

        $this->assertEquals($expected, $result);

    }

    public function test_get_specific_linked_item_info_without_all_fields()
    {
        // Create an example collection of items

        $items = collect([
            (object) [
                'id' => 1,
                'name' => 'Monitor',
                'brand' => "Asus",
            ],
            (object) [
                'id' => 2,
                'name' => 'Highland Cow',
                'url' => 'https://www.argos.co.uk/product/6793878',
            ],
            (object) [
                'id' => 3,
                'name' => 'T shirt',
                'url' => 'https://downthelinesurf.co.uk/products/123',
                'brand' => "Patagonia",
            ],
        ]);

        $expected = collect([
            [
                'id' => 3,
                'linkedShops' => false,
                'linkedBrands' => false,
            ],
        ]);

        $result = $this->wishlistService->getSpecificLinkedItemInfo($items, 3);

        $this->assertEquals($expected, $result);



    }
}

