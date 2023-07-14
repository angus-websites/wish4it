<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class FriendController extends Controller
{
    /**
     * Show the friends of a user
     */
    public function index()
    {
        return Inertia::render('Friends/Index', [
            'friends' => Auth::user()->friends()->get()
        ]);
    }
}
