<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;

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

        // Create 5 users
        \App\Models\User::factory()->count(5)->create()->each(function ($u){

            $wishlist = Wishlist::create(['title' => 'My wishlist']);
            $wishlist->users()->attach($u->id, ['role' => 'owner']);
            
        });

    }
}
