<?php

namespace Database\Factories;

use App\Models\DetailPemesanan;
use App\Models\Item;
use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DetailPemesanan>
 */
class DetailPemesananFactory extends Factory
{
    public function configure(): static
    {
        return $this
            ->afterMaking(function (DetailPemesanan $detailPemesanan): void {
                $this->syncTotalAmount($detailPemesanan);
            })
            ->afterCreating(function (DetailPemesanan $detailPemesanan): void {
                $this->syncTotalAmount($detailPemesanan, true);
            });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pemesanan_id' => Pemesanan::factory(),
            'item_id' => Item::factory(),
            'quantity' => fake()->numberBetween(1, 4),
            'total_amount' => 0,
        ];
    }

    private function syncTotalAmount(DetailPemesanan $detailPemesanan, bool $persist = false): void
    {
        $item = $detailPemesanan->item ?? Item::query()->find($detailPemesanan->item_id);

        $detailPemesanan->total_amount = ($item?->price ?? 0) * $detailPemesanan->quantity;

        if ($persist) {
            $detailPemesanan->saveQuietly();
        }
    }
}
