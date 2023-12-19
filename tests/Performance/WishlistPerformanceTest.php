<?php

namespace Tests\Performance;


use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Benchmark;

class WishlistPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function runBenchmark($userCount, $wishlistCount, $itemCount): void
    {
        // Create users
        $this->createData($userCount, $wishlistCount, $itemCount);

        // Create our test user
        $user = User::factory()->create();

        // Create a wishlist for our test user
        $wishlist = Wishlist::factory()->create();
        $wishlist->users()->attach($user->id, ['role' => 'owner']);

        // Time how long it takes to load the first wishlist with benchmark
        $time = Benchmark::measure(function () use ($user) {
            $this->actingAs($user)
                ->get(route('wishlists.show', $user->wishlists->first()));
        });


        // Output the time taken in seconds and milliseconds leftover
        $this->outputTime($time, 'load users only wishlist');

        // Assert it takes less than 1 second
        $this->assertTrue($time < 1000);

        // Pick a random wishlist to view
        $wishlist = Wishlist::all()->random();

        // Time how long it takes to load a random wishlist with benchmark
        $time = Benchmark::measure(function () use ($user, $wishlist) {
            $this->actingAs($user)
                ->get(route('wishlists.show', $wishlist));
        }, 50);

        // Output the time taken in seconds and milliseconds leftover
        $this->outputTime($time, 'view random wishlist as user');

        // Assert it takes less than 1 second
        $this->assertTrue($time < 1000);

        $wishlist = Wishlist::all()->random();

        // Time how long it takes to load a random wishlist as guest with benchmark
        $time = Benchmark::measure(function () use ($wishlist) {
            $this->get(route('wishlists.show', $wishlist));
        });

        // Output the time taken in seconds and milliseconds leftover
        $this->outputTime($time, 'view random wishlist as guest');

        // Assert it takes less than 1 second
        $this->assertTrue($time < 1000);


    }

    private function outputTime($time, $action): void
    {
        $seconds = floor($time / 1000);
        $milliseconds = $time % 1000;
        echo "\nTime taken to $action: $seconds seconds, $milliseconds milliseconds\n";
    }

    private function createData($userCount, $wishlistCount, $itemCount): void
    {
        // Create users
        User::factory()->count($userCount)
            ->hasAttached(
                Wishlist::factory()
                    ->count($wishlistCount)
                    ->public(true)
                    ->hasItems($itemCount),
                ['role' => 'owner'],
            )->create();

    }

    public function test_show_wishlist_with_fifteen_users(): void
    {
        // Takes around 4 seconds to generate 15 users
        $this->runBenchmark(15, 5, 40);

    }

    public function test_show_wishlist_with_hundred_users(): void
    {
        // Takes around 24 seconds to generate 100 users
        $this->runBenchmark(100, 5, 40);

    }

    public function test_show_wishlist_with_five_hundred_users(): void
    {

        // Takes around 4 minutes to generate 500 users
        $this->runBenchmark(500, 10, 40);

    }


    public function test_show_wishlist_with_thousand_users(): void
    {

        // Takes around 8 minutes to generate 1000 users
        $this->runBenchmark(1000, 10, 40);

    }

    public function test_route_model_binding(): void
    {
        // Create 150 users
        $this->createData(150, 5, 40);

        // Choose a random wishlist
        $wishlist = Wishlist::all()->random();

        // Benchmark the performance routes
        $time = Benchmark::measure(function () use ($wishlist) {
            $this->get(route('performance.load-wishlist', $wishlist));
        }, 50);

        // Display the time taken
        $this->outputTime($time, 'load wishlist with route model binding');

        // Assert it takes less than 1 second
        $this->assertTrue($time < 1000);

        // Choose a random wishlist
        $wishlist = Wishlist::all()->random();

        // Benchmark the route without route model binding
        $time = Benchmark::measure(function () use ($wishlist) {
            $this->get(route('performance.load-wishlist-without-route-model-binding', $wishlist->id));
        }, 50);

        // Display the time taken
        $this->outputTime($time, 'load wishlist without route model binding');

        // Assert it takes less than 1 second
        $this->assertTrue($time < 1000);

    }

}
