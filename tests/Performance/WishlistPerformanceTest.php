<?php

namespace Tests\Performance;


use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_wishlist(): void
    {
        // Create 1000 users with 5 wishlists each, with 50 products each
        User::factory()->count(1000)->create()->each(function ($u) {

            $wishlist = Wishlist::create();
            $wishlist->users()->attach($u->id, ['role' => 'owner']);

        });
    }

}
