<?php

namespace App\Models;

use Database\Factories\BeritaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Berita extends Model
{
    /** @use HasFactory<BeritaFactory> */
    use HasFactory;

    public const CategoryPengajian = 'Pengajian';

    public const CategoryPraktek = 'Praktek';

    public const CategoryEkstrakulikuler = 'Ekstrakulikuler';

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'date',
        'content',
        'image_url',
        'visible',
    ];

    /**
     * @return array<string, string>
     */
    public static function categoryOptions(): array
    {
        return [
            self::CategoryPengajian => self::CategoryPengajian,
            self::CategoryPraktek => self::CategoryPraktek,
            self::CategoryEkstrakulikuler => self::CategoryEkstrakulikuler,
        ];
    }

    public static function categorySlug(string $category): string
    {
        return Str::slug($category);
    }

    public static function categoryFromSlug(string $slug): ?string
    {
        return collect(array_keys(self::categoryOptions()))
            ->first(fn (string $category): bool => self::categorySlug($category) === $slug);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
