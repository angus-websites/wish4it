<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Wishlist extends Model
{
    use HasFactory;
    use HasUuids;

    // Guarded attributtes
    protected $guarded = ['id', 'user_id'];

    /**
     * Fetch the user
     * that this wishlist belongs
     * to
     */
    public function user(){
        return $this->belongsTo(User::class)->firstOrFail();
    }

    /**
     * Get the items for this wishlist
     */
    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * Is this wishlist public or not
     */
    public function isPublic()
    {
        return $this->public;
    }
}
