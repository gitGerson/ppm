<?php

namespace App\Models;

use Database\Factories\DetailPemesananFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPemesanan extends Model
{
    /** @use HasFactory<DetailPemesananFactory> */
    use HasFactory;

    protected $fillable = [
        'pemesanan_id',
        'item_id',
        'quantity',
        'total_amount',
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
