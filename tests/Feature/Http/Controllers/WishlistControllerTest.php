<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use App\Services\WishlistService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class WishlistControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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
     * Test the index page
     */
    public function test_index_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('wishlists.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($assert) => $assert
            ->component('Wishlist/Index')
            ->has('lists')
        );
    }
}
