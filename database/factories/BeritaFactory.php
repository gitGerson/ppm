<?php

namespace Database\Factories;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Berita>
 */
class BeritaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake('id_ID')->sentence(6),
            'date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'content' => fake('id_ID')->paragraphs(3, true),
            'image_url' => null,
            'visible' => fake()->boolean(85),
        ];
    }
}
