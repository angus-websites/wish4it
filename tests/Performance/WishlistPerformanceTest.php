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

    protected function runBenchmark($userCount, $wishlistCount, $itemCount, $targetTime = 1000): array
    {

        // Store results in an array
        $results = [];

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


        $action = 'load the only wishlist for a new user';
        $results[$action] = $time;


        // Assert it takes less than 1 second
        $this->assertTrue($time < $targetTime);

        // Pick a random wishlist to view
        $wishlist = Wishlist::all()->random();

        // Time how long it takes to load a random wishlist with benchmark
        $time = Benchmark::measure(function () use ($user, $wishlist) {
            $this->actingAs($user)
                ->get(route('wishlists.show', $wishlist));
        }, 25);

        $action = 'view random wishlist as user';
        $results[$action] = $time;

        // Assert it takes less than 1 second
        $this->assertTrue($time < $targetTime);

        $wishlist = Wishlist::all()->random();

        // Time how long it takes to load a random wishlist as guest with benchmark
        $time = Benchmark::measure(function () use ($wishlist) {
            $this->get(route('wishlists.show', $wishlist));
        }, 25);

        $action = 'view random wishlist as guest';
        $results[$action] = $time;

        // Assert it takes less than 1 second
        $this->assertTrue($time < $targetTime);

        $wishlist = Wishlist::all()->random();

        // Time how long it takes to run the performance benchmark
        $time = Benchmark::measure(function () use ($wishlist) {
            $this->get(route('performance.load-wishlist', $wishlist));
        });

        $action = 'visit benchmark route';
        $results[$action] = $time;

        $user = User::all()->random();

        // Time how long it do a series of actions as a user
        $time = Benchmark::measure(function () use ($user) {

            $this->actingAs($user);

            // Go to the create wishlist page
            $this->get(route('wishlists.create'));

            // Create a wishlist
            $this->post(route('wishlists.store'), Wishlist::factory()->make()->toArray());

        }, 25);

        $action = 'create a wishlist as user';
        $results[$action] = $time;

        $user = User::all()->random();

        // Time how long it do a series of actions as a user
        $time = Benchmark::measure(function () use ($user) {

            $this->actingAs($user);

            // Go to the update wishlist page
            $this->get(route('wishlists.edit', $user->wishlists->first()));

            // Update a wishlist
            $this->put(route('wishlists.update', $user->wishlists->first()), Wishlist::factory()->make()->toArray());

        }, 25);

        $action = 'update a wishlist as user';
        $results[$action] = $time;


        $this->outputResults($results);
        return $results;


    }

    private function outputResults($results): void
    {
        echo "\nPerformance Results:\n";
        echo "---------------------------------------------------\n";
        echo "| Action                        | Seconds |  Ms   |\n";
        echo "---------------------------------------------------\n";

        foreach ($results as $action => $time) {
            $seconds = floor($time / 1000);
            $milliseconds = $time % 1000;
            // Adjust the column widths in the format specifier as needed
            printf("| %-30s | %-7s | %-5s |\n", $action, $seconds, $milliseconds);
        }

        echo "---------------------------------------------------\n";
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


}
