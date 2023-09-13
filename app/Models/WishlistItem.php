<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = ['id', 'wishlist_id'];

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class)->firstOrFail();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getHasAttribute()
    {
        return $this->reservations->sum('quantity');
    }

    public function hasUserReservation(User $user)
    {
        return $this->reservations()->where('user_id', $user->id)->exists();
    }
}
