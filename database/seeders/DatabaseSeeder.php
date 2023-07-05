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
        // Create 10 users
        \App\Models\User::factory()->count(5)->create()->each(function ($u){

            Wishlist::create([
              'title' => 'My wishlist',
              'user_id' => $u->id
            ]);
            
        });

    }
}
