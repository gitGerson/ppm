<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Kegiatan Hero PPM',
                'source_path' => 'images/assets/hero.png',
                'image_path' => 'sliders/hero.png',
                'alt_text' => 'Kegiatan hero PPM',
                'sort_order' => 1,
            ],
            [
                'title' => 'Suasana Pembelajaran PPM',
                'source_path' => 'images/assets/hero2.png',
                'image_path' => 'sliders/hero2.png',
                'alt_text' => 'Suasana pembelajaran PPM',
                'sort_order' => 2,
            ],
        ];

        foreach ($sliders as $slider) {
            $sourcePath = public_path($slider['source_path']);
            $imagePath = $slider['image_path'];

            if (file_exists($sourcePath) && ! Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->put($imagePath, file_get_contents($sourcePath));
            }

            unset($slider['source_path']);

            Slider::query()->updateOrCreate(
                ['title' => $slider['title']],
                $slider + ['is_active' => true],
            );
        }
    }
}
