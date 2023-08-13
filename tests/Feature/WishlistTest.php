<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a non-logged-in user cannot view their wishlists
     */
    public function test_non_logged_in_user_cannot_access_wishlists(){
        $response = $this->get('/wishlists');
        $response->assertStatus(302);
    }

    /**
     * Test a logged-in user can view their wishlists
     */
    public function test_logged_in_user_can_access_wishlists()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/wishlists');
        $response->assertStatus(200);
    }

    /**
     * Test a non-logged-in user cannot create a wishlist
     */
    public function test_non_logged_in_user_cannot_create_wishlist()
    {
        $response = $this->post('/wishlists', [
            'title' => 'Test Wishlist',
            'public' => false,
        ]);
        $response->assertStatus(302);
    }

    /**
     * Test a logged-in user can create a wishlist
     */
    public function test_logged_in_user_can_create_wishlist(){
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/wishlists', [
            'title' => 'Test Wishlist',
            'public' => false,
        ]);
        $response->assertRedirect('/wishlists');
    }

    /**
     * Test a logged-in user receives an error when creating a wishlist with no title
     */
    public function test_logged_in_user_cannot_create_wishlist_without_title(){
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/wishlists', [
            'public' => false,
        ]);
        $response->assertSessionHasErrors('title');
    }

    /**
     * Test the owner of a wishlist can view a private wishlist
     */
    public function test_owner_can_view_private_wishlist(){
        $user = User::factory()->create();
        $wishlist = $user->wishlists()->create([
            'title' => 'Test Wishlist',
            'public' => false,
        ]);
        $response = $this->actingAs($user)->get('/wishlists/' . $wishlist->id);
        $response->assertStatus(200);
    }

    /**
     * Test another user cannot view a private wishlist
     */
    public function test_other_user_cannot_view_private_wishlist()
    {
        $user = User::factory()->create();
        $wishlist = $user->wishlists()->create([
            'title' => 'Test Wishlist',
            'public' => false,
        ]);
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->get('/wishlists/' . $wishlist->id);
        $response->assertStatus(403);
    }

    /**
     * Test a friend of the creator cannot view a private wishlist
     */
    public function test_friend_of_creator_cannot_view_private_wishlist(){
        $user = User::factory()->create();
        $wishlist = $user->wishlists()->create([
            'title' => 'Test Wishlist',
            'public' => false,
        ]);
        $otherUser = User::factory()->create();
        $otherUser->friends()->attach($user);
        $response = $this->actingAs($otherUser)->get('/wishlists/' . $wishlist->id);
        $response->assertStatus(403);
    }


    /**
     * Test other user cannot update a wishlist
     */
    public function test_other_user_cannot_update_wishlist(){
        $user = User::factory()->create();
        $wishlist = $user->wishlists()->create([
            'title' => 'Test Wishlist',
            'public' => false,
        ]);
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->put('/wishlists/' . $wishlist->id, [
            'title' => 'Updated Wishlist',
            'public' => true,
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test the wishlist is actually updated when the owner updates it
     */
    public function test_wishlist_is_updated_when_owner_updates_it()
    {
        $user = User::factory()->create();
        $wishlist = $user->wishlists()->create([
            'title' => 'Test Wishlist',
            'public' => false,
        ]);
        $response = $this->actingAs($user)->put('/wishlists/' . $wishlist->id, [
            'title' => 'Updated Wishlist',
            'public' => true,
        ]);
        $this->assertDatabaseHas('wishlists', ['id' => $wishlist->id, 'title' => 'Updated Wishlist', 'public' => true]);
    }

    /**
     * Test nobody but the creator can delete a wishlist
     */
    public function test_other_user_cannot_delete_wishlist()
    {
        $user = User::factory()->create();
        $wishlist = $user->wishlists()->create([
            'title' => 'Test Wishlist',
            'public' => false,
        ]);
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->delete('/wishlists/' . $wishlist->id);
        $response->assertStatus(403);
    }

    public function test_a_guest_redirects_to_previous_wishlist_after_login()
    {
        $user = User::factory()->create();
        $wishlist = $user->wishlists()->create([
            'title' => 'Test Wishlist',
            'public' => true,
        ]);

        $guest = User::factory()->create();
        // Simulate the behavior of a guest trying to view the wishlist
        $this->get(route('wishlists.show', $wishlist));

        // Simulate login
        $response = $this->post(route('login'), [
            'email' => $guest->email,
            'password' => 'password' // Assuming this is the default password you've set in your User factory.
        ]);

        $response->assertRedirect(route('wishlists.show', $wishlist));
    }
}
