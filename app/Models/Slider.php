<?php

namespace App\Models;

use Database\Factories\SliderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Slider extends Model
{
    /** @use HasFactory<SliderFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'alt_text',
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

    public function imageUrl(): string
    {
        if (Str::startsWith($this->image_path, ['http://', 'https://', 'images/'])) {
            return Str::startsWith($this->image_path, ['http://', 'https://'])
                ? $this->image_path
                : asset($this->image_path);
        }

        return asset('storage/'.$this->image_path);
    }
}
