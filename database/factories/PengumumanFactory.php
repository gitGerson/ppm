<?php

namespace Database\Factories;

use App\Models\Pengumuman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengumuman>
 */
class PengumumanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake('id_ID')->sentence(5),
            'date' => fake()->dateTimeBetween('-2 months', '+1 month')->format('Y-m-d'),
            'content' => fake('id_ID')->paragraphs(2, true),
            'image_url' => null,
            'visible' => fake()->boolean(90),
        ];
    }
}
