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
        //sleep(2);
        $search = $request->get('query');

        // Validate for empty searches
        if (empty($search)) {
            return response()->json([
                'success' => false,
                'message' => 'Query parameter is missing.'
            ]);
        }
        
        // Attempt to find a user matching the search
        $user = User::where('username', 'like', $search)->first();
        $friend = $user ? new FriendResource($user) : null;

        return response()->json([
            'success' => isset($user),
            'friend' => $friend
        ]);
    }
}
