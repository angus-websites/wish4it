<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

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
}
