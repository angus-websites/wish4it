<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\WishlistItem;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'wishlist_item_id' => 'required|exists:wishlist_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get item from the database
        $item = WishlistItem::findOrFail($request->wishlist_item_id);

        // Create the new reservation
        $reservation = new Reservation();
        $reservation->wishlist_item_id = $request->wishlist_item_id;
        $reservation->quantity = $request->quantity;
        $reservation->user_id = Auth::user()->id;  // Get the logged in user's ID

        $reservation->save();

        // Update the quantity of the wishlist item after the reservation
        $item->has = $item->has + $request->quantity;
        $item->save();

        return Redirect::back()->with('success', 'Marked as purchased');
    }
}
