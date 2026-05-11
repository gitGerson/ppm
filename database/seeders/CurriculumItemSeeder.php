<?php

namespace Database\Seeders;

use App\Models\CurriculumItem;
use Illuminate\Database\Seeder;

class CurriculumItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

        foreach ($items as $item) {
            CurriculumItem::query()->updateOrCreate(
                ['title' => $item['title']],
                $item + ['is_active' => true],
            );
        }
    }
}
