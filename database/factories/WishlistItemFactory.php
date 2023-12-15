<?php

namespace Database\Factories;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WishlistItem>
 */
class WishlistItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->sentence, // Generates a random sentence as the name
            'brand' => $this->faker->company, // Optional, generates a random company name
            'price' => $this->faker->randomNumber(2), // Generates a random number for the price
            'url' => $this->faker->url, // Generates a random URL
            'comment' => $this->faker->text, // Generates random text for the comment
            'image' => $this->faker->imageUrl, // Generates a random image URL
            'needs' => $this->faker->numberBetween(1, 10) // Random number between 1 and 10
        ];
    }
}
