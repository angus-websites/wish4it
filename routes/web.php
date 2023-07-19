<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\WishlistController;
use \App\Http\Controllers\WishlistItemController;
use \App\Http\Controllers\FriendController;
use \App\Http\Controllers\ProductScraperController;

use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('index');

// TODO move to auth
Route::post('/scrape-product', [ProductScraperController::class, 'scrapeProduct']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Wishlists
    Route::resource('wishlists', WishlistController::class);
    Route::resource('wishlists.items', WishlistItemController::class);

    // Friends page
    Route::get('/friends', [FriendController::class, 'index'])->name('friends');
    Route::post('/friends/search', [FriendController::class, 'search'])->name('friends.search');
    Route::post('/friends/add/{username}', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::delete('/friends/remove/{username}', [FriendController::class, 'removeFriend'])->name('friends.remove');


});


