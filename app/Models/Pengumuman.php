<?php

namespace App\Models;

use Database\Factories\PengumumanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    /** @use HasFactory<PengumumanFactory> */
    use HasFactory;

    protected $table = 'pengumumen';

    protected $fillable = [
        'title',
        'date',
        'content',
        'image_url',
        'visible',
    ];
}
