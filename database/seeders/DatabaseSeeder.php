<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the roles
        $this->call(RoleSeeder::class);
        // Create the admin
        $this->call(AdminSeeder::class);

        // Create the friend user first
        $friend = User::factory()->create();

        // Create the wishlists for the friend
        Wishlist::factory()
            ->count(3)
            ->hasItems(5)
            ->public(true)
            ->forUser($friend)
            ->create();

        // Create another friend user
        $friend2 = User::factory()->create();

        // Create the wishlists for the friend
        Wishlist::factory()
            ->count(3)
            ->hasItems(5)
            ->public(true)
            ->forUser($friend2)
            ->create();

        // Make friends the owner of the wishlist

        // Find the admin user
        $admin = User::where('email', "=", config('admin.admin_email'))->first();

        // Make the friend user a friend of the admin user
        $admin->friends()->attach($friend);
        $admin->friends()->attach($friend2);

    }
}
