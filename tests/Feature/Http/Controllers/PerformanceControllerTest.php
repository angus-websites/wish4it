<?php

namespace Feature\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerformanceControllerTest extends TestCase
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

    /**
     * Test the loadWishlist method
     */
    public function test_load_wishlist()
    {
        $response = $this->get(route('performance.load-wishlist', $this->public_wishlist));

        // Assert the response is successful
        $response->assertSuccessful();

        // Assert the response has two keys, wishlist and items
        $response->assertJsonCount(2);

    }

    /**
     * Test the loadWishlistWithoutRouteModelBinding method
     */
    public function test_load_wishlist_without_route_model_binding()
    {
        $response = $this->get(route('performance.load-wishlist-without-route-model-binding', $this->public_wishlist->id));

        // Assert the response is successful
        $response->assertSuccessful();

        // Assert the response has two keys, wishlist and items
        $response->assertJsonCount(2);
    }


}
