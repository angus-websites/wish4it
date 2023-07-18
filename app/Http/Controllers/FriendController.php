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

        // Eager load the wishlists for the resource
        $friends = Auth::user()->friends()->with('wishlists')->get();

        return Inertia::render('Friends/Index', [
            'friends' => FriendResource::collection($friends)
        ]);
    }

    /**
     * Add a friend
     */
    public function addFriend(Request $request)
    {
        sleep(5);

        // Extract the username from the request
        $username = $request->get('username');
        
        // Attempt to find a user with the given username
        $user = User::where('username', $username)->first();

        // If the user doesn't exist, return an error
        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // Get the currently authenticated user
        $currentUser = Auth::user();
        
        // If the user is the current user, return an error
        if($currentUser->id == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot add yourself as a friend.'
            ], 400);
        }

        // If the user is already a friend, return a message
        if($currentUser->friends()->where('friend_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This user is already your friend.'
            ], 400);
        }

        // Add the user as a friend
        $currentUser->friends()->attach($user->id);
        
        return response()->json([
            'success' => true,
            'message' => 'Friend added successfully.'
        ], 200);
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
