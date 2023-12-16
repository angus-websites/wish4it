<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $superAdminRole = Role::where('name', '=', 'Super Admin')->firstOrFail();
        if (config('admin.admin_name')) {
            $admin = User::create([
                'name' => config('admin.admin_name'),
                'email' => config('admin.admin_email'),
                'username' => config('admin.admin_username'),
                'role_id' => $superAdminRole->id,
                'password' => Hash::make(config('admin.admin_password')),
            ]);


            // Create 3 wishlists for the admin with 1-25 items and set the role to owner
            $admin->wishlists()->attach(
                Wishlist::factory()->count(3)
                    ->hasItems(rand(10, 30))
                    ->create()
                , ['role' => 'owner']
            );


        }

    }
}
