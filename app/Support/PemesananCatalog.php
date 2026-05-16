<?php

namespace App\Support;

class PemesananCatalog
{
    /**
     * @return array<string, array{name: string, price: int}>
     */
    public static function materials(): array
    {
        return [
            'al_quran_makna' => [
                'name' => 'Al-Quran Makna',
                'price' => 85000,
            ],
            'hadist_himpunan_4_jilid' => [
                'name' => 'Hadist Himpunan 4 Jilid',
                'price' => 140000,
            ],
            'materi_kelas_2_jilid' => [
                'name' => 'Materi Kelas 2 Jilid',
                'price' => 60000,
            ],
            'baju_asad' => [
                'name' => 'Baju Asad',
                'price' => 150000,
            ],
            'seragam_ppm' => [
                'name' => 'Seragam PPM',
                'price' => 150000,
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function sizes(): array
    {
        return ['S', 'M', 'L', 'XL', 'XXL'];
    }

    /**
     * @param  array<int, string>  $selectedMaterials
     */
    public static function totalFor(array $selectedMaterials): int
    {
        $materials = self::materials();

        return collect($selectedMaterials)
            ->sum(fn (string $key): int => $materials[$key]['price'] ?? 0);
    }
}
