<?php

namespace App\Models;

use Database\Factories\PemesananFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemesanan extends Model
{
    /** @use HasFactory<PemesananFactory> */
    use HasFactory;

    protected $fillable = [
        'order_date',
        'address',
        'total_amount',
        'nama',
    ];

    public function detailPemesanans(): HasMany
    {
        return $this->hasMany(DetailPemesanan::class);
    }
}
