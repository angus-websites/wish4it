<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wishlist;
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
        
        // Create 10 users with 3 wishlists each with 1-25 items
        User::factory()->count(10)
            ->hasAttached(
                Wishlist::factory()->count(3)
                    ->hasItems(rand(1,25)),
                ['role' => 'owner'],
            )
            ->create();

    }
}
