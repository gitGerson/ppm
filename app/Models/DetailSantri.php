<?php

namespace App\Models;

use App\Observers\DetailSantriObserver;
use Database\Factories\DetailSantriFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(DetailSantriObserver::class)]
class DetailSantri extends Model
{
    /** @use HasFactory<DetailSantriFactory> */
    use HasFactory;

    protected $table = 'detail_santris';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nama_panggilan',
        'NISN',
        'NIK',
        'tanggal_lahir',
        'tempat_lahir',
        'status_anak',
        'anak_ke',
        'jumlah_saudara',
        'hobi',
        'cita-cita',
        'jenis_kelamin',
        'tinggi_badan',
        'berat_badan',
        'golongan_darah',
        'riwayat_penyakit',
        'status_bpjs',
        'status_pip',
        'ijazah_terakhir',
        'nama_sekolah_asal',
        'prodi_sekolah_asal',
        'tahun_masuk_sekolah',
        'tahun_masuk_ppm',
        'is_mondok',
        'khatam',
        'asalkelompoksambung',
        'desa',
        'alamat',
        'rtrw',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kodepos',
        'no_hp',
        'email',
        'media_sosial',
        'is_motor',
        'is_sepeda',
        'is_laptop',
        'sim',
        'image_ktp_path',
        'image_pasfoto_path',
        'no_kk',
        'nama_ayah',
        'nik_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'no_hp_ayah',
        'nama_ibu',
        'nik_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'no_hp_ibu',
        'is_ayah_alive',
        'is_ibu_alive',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sheet_synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
