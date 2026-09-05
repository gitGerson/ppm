<?php

namespace App\Models;

use App\Support\SantriSheetSchema;
use Database\Factories\DetailSantriFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class DetailSantri extends Model
{
    /** @use HasFactory<DetailSantriFactory> */
    use HasFactory;

    public const MAX_ANAK_KE = 255;

    public const MAX_JUMLAH_SAUDARA = 255;

    /** @return array<string, array<mixed>> */
    public static function sheetUpdateRules(): array
    {
        $rules = [];

        foreach (SantriSheetSchema::columns() as $field => $column) {
            if (! $column['editable']) {
                continue;
            }

            $limits = match ($field) {
                'anak_ke', 'jumlah_saudara' => 255,
                'tinggi_badan', 'berat_badan' => 65535,
                default => 4294967295,
            };
            $length = match ($field) {
                'NISN' => 20,
                'NIK', 'no_kk', 'nik_ayah', 'nik_ibu' => 16,
                default => 255,
            };
            $typeRules = match ($column['type']) {
                'bool' => ['boolean'],
                'int' => ['integer', 'min:0', 'max:'.$limits],
                'year' => ['integer', 'between:1901,2155'],
                'date' => ['date_format:Y-m-d', 'after_or_equal:1000-01-01', 'before_or_equal:9999-12-31'],
                'enum' => ['string', Rule::in($column['values'])],
                default => ['string', 'max:'.$length],
            };

            $rules[$field] = [
                'sometimes',
                $field === 'nama_lengkap' ? 'required' : 'nullable',
                ...$typeRules,
                ...($field === 'email' ? ['email'] : []),
            ];
        }

        return $rules;
    }

    /** @return array<string, string> */
    public static function sheetValidationMessages(): array
    {
        return [
            'changes.nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'changes.array' => 'Perubahan berisi kolom yang tidak dapat diedit.',
            'changes.*.max' => 'Nilai melebihi batas kolom.',
            'changes.*.date_format' => 'Gunakan format tanggal YYYY-MM-DD.',
        ];
    }

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
            'pendaftaran_notified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
