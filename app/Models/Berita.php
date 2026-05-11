<?php

namespace App\Models;

use Database\Factories\BeritaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    /** @use HasFactory<BeritaFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'date',
        'content',
        'image_url',
        'visible',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
