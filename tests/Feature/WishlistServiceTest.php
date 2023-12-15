<?php

namespace Tests\Feature;

use Database\Factories\WishlistFactory;
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

}

