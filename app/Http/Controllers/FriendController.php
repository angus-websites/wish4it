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
        sleep(5);
        $search = $request->get('query');
        $users = User::where('username', 'like', $search)->get();
        return response()->json($users);
    }
}
