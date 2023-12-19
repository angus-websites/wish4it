<?php

namespace Tests\Performance;


use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Benchmark;

class WishlistItemCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_cache()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a wishlist for our test user
        $wishlist = Wishlist::factory()->create();
        $wishlist->users()->attach($user->id, ['role' => 'owner']);

        // Create 100 items
        WishlistItem::factory()->count(100)->create(['wishlist_id' => $wishlist->id]);

        // Time how long it takes to view the wishlist
        $time = Benchmark::measure(function () use ($user, $wishlist) {
            $this->actingAs($user)
                ->get(route('wishlists.show', $wishlist));
        }, );

        // Time how long it takes to view the wishlist again
        $time2 = Benchmark::measure(function () use ($user, $wishlist) {
            $this->actingAs($user)
                ->get(route('wishlists.show', $wishlist));
        }, );

        // Assert the second time is faster
        $this->assertTrue($time2 < $time);

        // Display the results in seconds
        $time = $time / 1000;
        $time2 = $time2 / 1000;
        echo "First time: $time seconds\n";
        echo "Second time: $time2 seconds\n";


    }
}
