<?php

use App\Http\Controllers\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductScraperController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WishlistItemController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'home'])->name('index');

//Storage
Route::get('/images/{path}', [StorageController::class, 'image'])->where('path', '.*');
Route::get('/storage/{path}', [StorageController::class, 'storage'])->where('path', '.*');

// Public wishlists
Route::get('wishlists/{wishlist}', [WishlistController::class, 'show'])->name('wishlists.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Wishlists
    Route::resource('wishlists', WishlistController::class)->except('show');
    Route::resource('wishlists.items', WishlistItemController::class);
    Route::put('/wishlists/{wishlist}/items/{item}/mark', [WishlistItemController::class, 'markAsPurchased'])->name('wishlists.items.mark');

    // Friends page
    Route::get('/friends', [FriendController::class, 'index'])->name('friends');
    Route::post('/friends/search', [FriendController::class, 'search'])->name('friends.search');
    Route::post('/friends/add/{username}', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::delete('/friends/remove/{username}', [FriendController::class, 'removeFriend'])->name('friends.remove');

    // Scape products
    Route::post('/scrape-product', [ProductScraperController::class, 'scrapeProduct'])->name('scrape');

});
