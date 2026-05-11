<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Kaos PPM',
                'Jaket Almamater',
                'Buku Catatan Santri',
                'Tumbler PPM',
                'Totebag PPM',
                'Mug PPM',
            ]),
            'price' => fake()->numberBetween(25000, 250000),
            'image_url' => null,
            'size' => fake()->optional()->randomElement(['S', 'M', 'L', 'XL']),
        ];
    }
}
