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

      $superAdminRole=Role::where('name', '=', 'Super Admin')->firstOrFail();
      if(config('admin.admin_name')) {
        $admin = User::create([
          'name' => config('admin.admin_name'),
          'email' => config('admin.admin_email'),
          'username' => config('admin.admin_username'),
          'role_id' => $superAdminRole->id,
          'password' => Hash::make(config('admin.admin_password')),
        ]);

        // Create an christmas wishlist
        $christmas = Wishlist::create(['title' => 'Birthday list']);
        $christmas->users()->attach($admin->id, ['role' => 'owner']);

        WishlistItem::create([
          'name' => "Air force 1",
          'price' => "£100",
          'wishlist_id' => $christmas->id
        ]);

        WishlistItem::create([
          'name' => "Puppy",
          'wishlist_id' => $christmas->id
        ]);


        $birthday = Wishlist::create(['title' => 'Birthday list']);
        $birthday->users()->attach($admin->id, ['role' => 'owner']);

        WishlistItem::create([
          'name' => "iMac",
          'price' => "£1,200",
          'wishlist_id' => $birthday->id
        ]);



      }

    }
}
