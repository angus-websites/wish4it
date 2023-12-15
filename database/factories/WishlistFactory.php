<?php

namespace Database\Factories;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Wishlist>
 */
class WishlistFactory extends Factory
{

    protected $model = Wishlist::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'title' => $this->faker->sentence,
            'public' => $this->faker->boolean,
        ];
    }


}
