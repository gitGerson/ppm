<?php

namespace Database\Seeders;

use App\Models\DetailPemesanan;
use App\Models\Item;
use App\Models\Pemesanan;
use Illuminate\Database\Seeder;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        $items = Item::query()->get();

        if ($items->isEmpty()) {
            $items = Item::factory()->count(15)->create();
        }

        Pemesanan::factory()
            ->count(12)
            ->create()
            ->each(function (Pemesanan $pemesanan) use ($items): void {
                $selectedItems = $items->random(rand(1, min(4, $items->count())));
                $totalAmount = 0;

                foreach ($selectedItems as $item) {
                    $detail = DetailPemesanan::factory()
                        ->for($pemesanan)
                        ->for($item)
                        ->state([
                            'quantity' => fake()->numberBetween(1, 3),
                        ])
                        ->create();

                    $totalAmount += $detail->total_amount;
                }

                $pemesanan->update([
                    'total_amount' => $totalAmount,
                ]);
            });
    }
}
