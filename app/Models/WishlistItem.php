<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'wishlist_id'];

    public function wishlist(){
        return $this->belongsTo(Wishlist::class)->firstOrFail();
    }
}
