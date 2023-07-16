<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\FriendResource;


class FriendController extends Controller
{
    /**
     * Show the friends of a user
     */
    public function index()
    {
        return Inertia::render('Friends/Index', [
            'friends' => FriendResource::collection(Auth::user()->friends()->get())
        ]);
    }

    /**
     * Search for users by username
     */
    public function search(Request $request)
    {
        $search = $request->get('query');

        // Validate for empty searches
        if (empty($search)) {
            return response()->json([
                'success' => false,
                'errorTitle' => 'No input',
                'message' => 'Please enter something'
            ]);
        }

        // Get the currently authenticated user
        $currentUser = Auth::user();

        // If the authenticated user searches for themselves
        if(strcasecmp($currentUser->username, $search) == 0) {
            return response()->json([
                'success' => false,
                'errorTitle' => 'That is sad',
                'message' => 'You cannot add yourself as a friend'
            ]);
        }

        // Attempt to find a user matching the search
        $user = User::where('username', 'like', $search)->first();
        
        // If the user doesn't exist
        if(is_null($user)) {
            return response()->json([
                'success' => false,
                'errorTitle' => 'User not found',
                'message' => 'A user with that username was not found'
            ]);
        }

        // Check if the found user is already a friend
        $friends = $currentUser->friends;
        if ($friends->contains($user->id)) {
            return response()->json([
                'success' => false,
                'errorTitle' => 'Friend exists',
                'message' => 'This user is already your friend'
            ]);
        }

        // If the user is not already a friend, return their details
        $friend = new FriendResource($user);

        return response()->json([
            'success' => true,
            'friend' => $friend
        ]);
    }

}
