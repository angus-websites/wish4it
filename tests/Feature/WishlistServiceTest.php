<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Cache;
use App\Services\WishlistService;

class WishlistServiceTest extends TestCase
{
    use RefreshDatabase;
    protected WishlistService $wishlistService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wishlistService = new WishlistService();

        // Create a user with a wishlist and 5 items
        $this->user = User::factory()
            ->hasAttached(
                Wishlist::factory()
                    ->hasItems(5),
                ['role' => 'owner'],
            )
            ->create();
    }

    /**
     * Test that the service can fetch a user's wishlist
     */
    public function test_fetches_valid_wishlist()
    {
        // Fetch the first wishlist
        $wishlist = $this->user->wishlists()->first();

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
        $wishlist = $this->user->wishlists()->first();

        // Call the service
        $wishlist_exists = $this->wishlistService->wishlistExists($wishlist->id);

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
        $wishlist_exists = $this->wishlistService->wishlistExists('invalid-id');

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
        ]);

        // Assert it exists in the database
        $this->assertDatabaseHas('wishlists', [
            'title' => 'Test Wishlist',
            'public' => true,
        ]);

    }

    /**
     * Test updating a wishlist
     */
    public function test_update_wishlist()
    {
        // Fetch the first wishlist
        $wishlist = $this->user->wishlists()->first();

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
     * Test deleting a wishlist
     */
    public function test_delete_wishlist()
    {
        // Fetch the first wishlist
        $wishlist = $this->user->wishlists()->first();

        // Delete the wishlist
        $this->wishlistService->deleteWishlist($wishlist);

        // Assert it exists in the database
        $this->assertDatabaseMissing('wishlists', [
            'id' => $wishlist->id,
        ]);
    }

}

