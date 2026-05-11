<?php

namespace App\Models;

use Database\Factories\CurriculumItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumItem extends Model
{
    /** @use HasFactory<CurriculumItemFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'theme',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
