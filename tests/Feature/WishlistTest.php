<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Reservation;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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


    /**
     * If a guest views a wishlist, then signs in or registers, they are
     * redirected to the list they viewed
     */
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

    /**
     * Only the author of a list should have the option
     * to view purchased items
     */
    public function test_only_author_can_view_purchased_items()
    {

        // Create the wishlist author and a new wishlist
        $author = User::factory()->create();
        $wishlist = $author->wishlists()->create([
            'title' => 'My public Wishlist',
            'public' => true,
        ]);

        // Disable guarded attributes
        \Illuminate\Database\Eloquent\Model::unguard();

        // Create an item on the wishlist to be purchased
        $ipad = WishlistItem::create([
          'name' => "iPad",
          'wishlist_id' => $wishlist->id
        ]);

        // Create an item on the wishlist to not be purchased
        $puppy = WishlistItem::create([
          'name' => "Puppy",
          'wishlist_id' => $wishlist->id
        ]);

        // Create a buyer
        $buyer = User::factory()->create();

        // Create a new reservation to mark as purchased
        $reservation = Reservation::create([
            'user_id' => $buyer->id,
            'wishlist_item_id' => $ipad->id,
            'quantity' => 1,
        ]);

        // Enable guarded attributes
        \Illuminate\Database\Eloquent\Model::reguard();

        // Create another user as another friend of the author
        $friend = User::factory()->create();

        // View the wishlist as the friend
        $this->actingAs($friend)->get('/wishlists/'.$wishlist->id)->assertInertia(fn(Assert $page) => $page
            ->component('Wishlist/View')
            // Checking we have 1 item in our array
            ->has('list.items', 1, fn(Assert $page) => $page
                ->where('id', $puppy->id)
                ->where('name', 'Puppy')
                ->etc()
            )
        );

        // View the wishlist as the author (both items should be present)
        $this->actingAs($author)->get('/wishlists/'.$wishlist->id)->assertInertia(fn(Assert $page) => $page
            ->component('Wishlist/View')
            // Checking we have 2 items in our array
            ->has('list.items', 2, fn(Assert $page) => $page
                ->where('id', $ipad->id)
                ->where('name', 'iPad')
                ->etc()
            )
        );




    }


}
