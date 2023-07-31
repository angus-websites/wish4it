<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test the login page is a valid route
     */
    public function test_login_form_displayed()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }


    /**
     * Tests the correct validation errors
     * appear when we attempt to login
     */
    public function test_login_displays_validation_errors()
    {
        $response = $this->post('/login', []);
        $response->assertStatus(302);

        // Test that we get error messages for missing fields
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('password');

    }

    /**
     * If we try to login with fake credentials we should get
     * an error saying the user is not found
     */
    public function test_login_with_fake_credentials_gives_user_not_found()
    {
        $response = $this->post('/login', ['email' => 'fake@gmail.com', 'password' => 'password']);
        $response->assertStatus(302);

        // We test for the email field because this is what the
        $response->assertSessionHasErrors('email');
        $response->assertSessionDoesntHaveErrors('password');

    }

    /**
     * Test that if we login
     * with correct credentials we get redirected to the dashboard
     */
    public function test_login_with_valid_credentials_redirects_to_dashboard()
    {
        // Create an example user
        $user = User::factory()->create();

        // Login with credentials
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        // Check that we get redirected
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticatedAs($user);

    }

    /**
     * Test users cannot login with invalid password
     */
    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }


}
