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
    public function test_index_page_for_normal_user()
    {
        $response = $this->actingAs($this->user)
            ->get(route('wishlists.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($assert) => $assert
            ->component('Wishlist/Index')
            ->has('lists')
        );
    }

    /**
     * Test the index page for a guest
     */
    public function test_index_page_for_guest()
    {
        $response = $this->get(route('wishlists.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test the store route
     */
    public function test_store_route_for_normal_user()
    {
        $response = $this->actingAs($this->user)
            ->post(route('wishlists.store'), [
                'title' => 'Test Wishlist',
                'public' => true,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Wishlist created');

        // Assert that the wishlist was created
        $this->assertDatabaseHas('wishlists', [
            'title' => 'Test Wishlist',
            'public' => true,
        ]);
    }

    /**
     * Test the store route for a guest
     */
    public function test_store_route_for_guest()
    {
        $response = $this->post(route('wishlists.store'), [
            'title' => 'Test Wishlist',
            'public' => true,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        // Assert that the wishlist was not created
        $this->assertDatabaseMissing('wishlists', [
            'title' => 'Test Wishlist',
            'public' => true,
        ]);
    }

    /**
     * Test store route without a title
     */
    public function test_store_route_validation()
    {

        $this->actingAs($this->user);

        // Test without a title
        $response = $this->post(route('wishlists.store'), [
            'public' => true,
        ]);


        $response->assertStatus(302);
        $response->assertSessionHasErrors('title');

        // Test without a public value
        $response = $this->post(route('wishlists.store'), [
            'title' => 'Test Wishlist',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('public');

        // Test without any data
        $response = $this->post(route('wishlists.store'));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'public']);
    }



}
