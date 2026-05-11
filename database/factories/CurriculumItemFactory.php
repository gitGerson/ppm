<?php

namespace Database\Factories;

use App\Models\CurriculumItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CurriculumItem>
 */
class CurriculumItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $items = [
            [
                'title' => 'Pegon Bacaan',
                'description' => 'Pembiasaan membaca teks pegon untuk memperkuat dasar kitab dan ketelitian lafaz.',
                'icon' => 'book',
                'theme' => 'sage',
                'sort_order' => 1,
            ],
            [
                'title' => 'Lambatan',
                'description' => 'Kelas bertahap dengan pendampingan intensif agar santri membangun pemahaman dari fondasi.',
                'icon' => 'layers',
                'theme' => 'sand',
                'sort_order' => 2,
            ],
            [
                'title' => 'Cepatan',
                'description' => 'Jalur percepatan untuk santri yang siap menempuh materi lebih padat dan ritme lebih tinggi.',
                'icon' => 'spark',
                'theme' => 'mint',
                'sort_order' => 3,
            ],
            [
                'title' => 'Saringan',
                'description' => 'Tahap evaluasi untuk memastikan pemahaman, kelancaran, dan kesiapan menuju jenjang berikutnya.',
                'icon' => 'shield',
                'theme' => 'olive',
                'sort_order' => 4,
            ],
            [
                'title' => 'Hadist Besar',
                'description' => 'Pendalaman kitab hadist pilihan sebagai penguatan wawasan ilmiah dan adab keilmuan santri.',
                'icon' => 'star',
                'theme' => 'sky',
                'sort_order' => 5,
            ],
        ];

        $item = fake()->randomElement($items);

        return [
            'title' => $item['title'],
            'description' => $item['description'],
            'icon' => $item['icon'],
            'theme' => $item['theme'],
            'sort_order' => $item['sort_order'],
            'is_active' => true,
        ];
    }
}
