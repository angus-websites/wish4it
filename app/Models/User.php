<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasUuids;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the role for this
     * user, Note: the field
     * is nullable so not all users
     * will have a role
     */
    private function role() {
        return $this->belongsTo(Role::class)->first();
    }

    public function getRole(){
        return $this->role();
    }

    /**
     * Is this user admin or super admin?
     */
    public function isAdmin($super=false){
        
        // Check this user actually has a role
        if ($this->role()){
            return $super ? $this->role()->name == "Super Admin" :  in_array($this->role()->name, ["Admin", "Super Admin"]);
        }
        return false;
    }

    /**
     * Get the wishlists for this user
     */
    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class)->withPivot('role');
    }

    /**
     * Custom method to quickly create wishlists
     */
    public function createWishlist($attributes = [])
    {
        // Create a new wishlist
        $wishlist = Wishlist::create($attributes);

        // Attach this user to the wishlist as an owner
        $this->wishlists()->attach($wishlist->id, ['role' => 'owner']);

        // Return the wishlist for further use
        return $wishlist;
    }


    /**
     * Get the friends of this user
     */
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id');
    }

    /**
     * Check if this user is friends with
     * another user
     */
    public function isFriends(User $user)
    {
        return $this->friends->contains($user);
    }
}
