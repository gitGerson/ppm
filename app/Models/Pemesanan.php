<?php

namespace App\Models;

use Database\Factories\PemesananFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemesanan extends Model
{
    /** @use HasFactory<PemesananFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_date',
        'address',
        'total_amount',
        'nama',
        'nama_kos',
        'payment_status',
        'seragam_ppm_size',
        'baju_asad_size',
        'bukti_pembayaran_path',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailPemesanans(): HasMany
    {
        return $this->hasMany(DetailPemesanan::class);
    }
}
