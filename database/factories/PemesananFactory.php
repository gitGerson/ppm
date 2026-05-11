<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pemesanan>
 */
class PemesananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'address' => str_replace("\n", ', ', fake('id_ID')->address()),
            'nama' => fake('id_ID')->name(),
            'total_amount' => 0,
        ];
    }
}
