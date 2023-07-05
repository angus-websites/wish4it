<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\WishlistController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Wishlist home page
    Route::get('/lists', [WishlistController::class, 'index'])->name('lists');

    // Friends page
    Route::get('/friends', function () {
        return Inertia::render('Friends');
    })->name('friends');
});


