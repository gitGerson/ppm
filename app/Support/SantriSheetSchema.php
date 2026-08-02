<?php

namespace App\Support;

use App\Models\DetailSantri;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

/**
 * Column map translating a DetailSantri row to and from a Google Sheets row.
 *
 * Column order here is the column order in the sheet, so appending a new entry
 * at the end is safe while reordering is not.
 */
class SantriSheetSchema
{
    /**
     * Identifier-ish values Sheets would otherwise mangle: "0812..." loses its
     * leading zero and a 16 digit NIK turns into scientific notation. Writing
     * them with a leading apostrophe forces Sheets to store them as text.
     */
    private const TEXT_TYPES = ['identifier'];

    /**
     * @return array<string, array{label: string, type: string, editable: bool, values?: array<int, string>}>
     */
    public static function columns(): array
    {
        return [
            'id' => ['label' => 'ID', 'type' => 'int', 'editable' => false],
            'user_id' => ['label' => 'User ID', 'type' => 'int', 'editable' => false],
            'nama_lengkap' => ['label' => 'Nama Lengkap', 'type' => 'string', 'editable' => true],
            'nama_panggilan' => ['label' => 'Nama Panggilan', 'type' => 'string', 'editable' => true],
            'NISN' => ['label' => 'NISN', 'type' => 'identifier', 'editable' => true],
            'NIK' => ['label' => 'NIK', 'type' => 'identifier', 'editable' => true],
            'tanggal_lahir' => ['label' => 'Tanggal Lahir', 'type' => 'date', 'editable' => true],
            'tempat_lahir' => ['label' => 'Tempat Lahir', 'type' => 'string', 'editable' => true],
            'status_anak' => ['label' => 'Status Anak', 'type' => 'string', 'editable' => true],
            'anak_ke' => ['label' => 'Anak Ke', 'type' => 'int', 'editable' => true],
            'jumlah_saudara' => ['label' => 'Jumlah Saudara', 'type' => 'int', 'editable' => true],
            'hobi' => ['label' => 'Hobi', 'type' => 'string', 'editable' => true],
            'cita-cita' => ['label' => 'Cita-cita', 'type' => 'string', 'editable' => true],
            'jenis_kelamin' => [
                'label' => 'Jenis Kelamin',
                'type' => 'enum',
                'editable' => true,
                'values' => ['Laki-laki', 'Perempuan'],
            ],
            'tinggi_badan' => ['label' => 'Tinggi Badan (cm)', 'type' => 'int', 'editable' => true],
            'berat_badan' => ['label' => 'Berat Badan (kg)', 'type' => 'int', 'editable' => true],
            'golongan_darah' => ['label' => 'Golongan Darah', 'type' => 'string', 'editable' => true],
            'riwayat_penyakit' => ['label' => 'Riwayat Penyakit', 'type' => 'string', 'editable' => true],
            'status_bpjs' => [
                'label' => 'Status BPJS',
                'type' => 'enum',
                'editable' => true,
                'values' => ['Tidak Memiliki', 'Memiliki', 'Memiliki KIS'],
            ],
            'status_pip' => [
                'label' => 'Status PIP',
                'type' => 'enum',
                'editable' => true,
                'values' => ['Tidak Memiliki', 'Memiliki'],
            ],
            'ijazah_terakhir' => ['label' => 'Ijazah Terakhir', 'type' => 'string', 'editable' => true],
            'nama_sekolah_asal' => ['label' => 'Nama Sekolah Asal', 'type' => 'string', 'editable' => true],
            'prodi_sekolah_asal' => ['label' => 'Prodi Sekolah Asal', 'type' => 'string', 'editable' => true],
            'tahun_masuk_sekolah' => ['label' => 'Tahun Masuk Sekolah', 'type' => 'year', 'editable' => true],
            'tahun_masuk_ppm' => ['label' => 'Tahun Masuk PPM', 'type' => 'year', 'editable' => true],
            'is_mondok' => ['label' => 'Mondok', 'type' => 'bool', 'editable' => true],
            'khatam' => ['label' => 'Khatam', 'type' => 'string', 'editable' => true],
            'asalkelompoksambung' => ['label' => 'Asal Kelompok Sambung', 'type' => 'string', 'editable' => true],
            'desa' => ['label' => 'Desa', 'type' => 'string', 'editable' => true],
            'alamat' => ['label' => 'Alamat', 'type' => 'string', 'editable' => true],
            'rtrw' => ['label' => 'RT/RW', 'type' => 'identifier', 'editable' => true],
            'provinsi' => ['label' => 'Provinsi', 'type' => 'string', 'editable' => true],
            'kabupaten' => ['label' => 'Kabupaten', 'type' => 'string', 'editable' => true],
            'kecamatan' => ['label' => 'Kecamatan', 'type' => 'string', 'editable' => true],
            'kodepos' => ['label' => 'Kode Pos', 'type' => 'identifier', 'editable' => true],
            'no_hp' => ['label' => 'No. HP', 'type' => 'identifier', 'editable' => true],
            'email' => ['label' => 'Email', 'type' => 'string', 'editable' => true],
            'media_sosial' => ['label' => 'Media Sosial', 'type' => 'string', 'editable' => true],
            'is_motor' => ['label' => 'Bawa Motor', 'type' => 'bool', 'editable' => true],
            'is_sepeda' => ['label' => 'Bawa Sepeda', 'type' => 'bool', 'editable' => true],
            'is_laptop' => ['label' => 'Bawa Laptop', 'type' => 'bool', 'editable' => true],
            'sim' => ['label' => 'SIM', 'type' => 'string', 'editable' => true],
            // Upload paths are owned by the app; an edited path would point the
            // UI at a file that does not exist, so they stay read-only.
            'image_ktp_path' => ['label' => 'Path KTP', 'type' => 'string', 'editable' => false],
            'image_pasfoto_path' => ['label' => 'Path Pasfoto', 'type' => 'string', 'editable' => false],
            'no_kk' => ['label' => 'No. KK', 'type' => 'identifier', 'editable' => true],
            'nama_ayah' => ['label' => 'Nama Ayah', 'type' => 'string', 'editable' => true],
            'nik_ayah' => ['label' => 'NIK Ayah', 'type' => 'identifier', 'editable' => true],
            'tempat_lahir_ayah' => ['label' => 'Tempat Lahir Ayah', 'type' => 'string', 'editable' => true],
            'tanggal_lahir_ayah' => ['label' => 'Tanggal Lahir Ayah', 'type' => 'date', 'editable' => true],
            'pendidikan_ayah' => ['label' => 'Pendidikan Ayah', 'type' => 'string', 'editable' => true],
            'pekerjaan_ayah' => ['label' => 'Pekerjaan Ayah', 'type' => 'string', 'editable' => true],
            'penghasilan_ayah' => ['label' => 'Penghasilan Ayah', 'type' => 'int', 'editable' => true],
            'no_hp_ayah' => ['label' => 'No. HP Ayah', 'type' => 'identifier', 'editable' => true],
            'is_ayah_alive' => ['label' => 'Ayah Masih Hidup', 'type' => 'bool', 'editable' => true],
            'nama_ibu' => ['label' => 'Nama Ibu', 'type' => 'string', 'editable' => true],
            'nik_ibu' => ['label' => 'NIK Ibu', 'type' => 'identifier', 'editable' => true],
            'tempat_lahir_ibu' => ['label' => 'Tempat Lahir Ibu', 'type' => 'string', 'editable' => true],
            'tanggal_lahir_ibu' => ['label' => 'Tanggal Lahir Ibu', 'type' => 'date', 'editable' => true],
            'pendidikan_ibu' => ['label' => 'Pendidikan Ibu', 'type' => 'string', 'editable' => true],
            'pekerjaan_ibu' => ['label' => 'Pekerjaan Ibu', 'type' => 'string', 'editable' => true],
            'penghasilan_ibu' => ['label' => 'Penghasilan Ibu', 'type' => 'int', 'editable' => true],
            'no_hp_ibu' => ['label' => 'No. HP Ibu', 'type' => 'identifier', 'editable' => true],
            'is_ibu_alive' => ['label' => 'Ibu Masih Hidup', 'type' => 'bool', 'editable' => true],
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function headers(): array
    {
        return array_values(array_map(
            fn (array $column): string => $column['label'],
            self::columns()
        ));
    }

    /**
     * @return array<int, string>
     */
    public static function dbColumns(): array
    {
        return array_keys(self::columns());
    }

    /**
     * @return array<int, string>
     */
    public static function editableDbColumns(): array
    {
        return array_keys(array_filter(
            self::columns(),
            fn (array $column): bool => $column['editable']
        ));
    }

    /**
     * Last sheet column letter, e.g. "BL" — used to build A1 ranges.
     */
    public static function lastColumnLetter(): string
    {
        return self::columnLetter(count(self::columns()));
    }

    /**
     * 1-based column index to its A1 letter (1 => A, 27 => AA).
     */
    public static function columnLetter(int $index): string
    {
        $letter = '';

        while ($index > 0) {
            $remainder = ($index - 1) % 26;
            $letter = chr(65 + $remainder).$letter;
            $index = intdiv($index - 1, 26);
        }

        return $letter;
    }

    /**
     * Model to the flat string row written into the sheet.
     *
     * @return array<int, string>
     */
    public static function toSheetRow(DetailSantri $santri): array
    {
        $row = [];

        foreach (self::columns() as $dbColumn => $definition) {
            $row[] = self::formatForSheet($santri->getAttribute($dbColumn), $definition['type']);
        }

        return $row;
    }

    /**
     * Sheet row to model attributes, dropping anything unparseable.
     *
     * Only editable columns come back — the rest are the app's to own — so a
     * malformed date or an unknown enum is skipped rather than written as null,
     * which would silently erase good data on a typo.
     *
     * @param  array<int, string|null>  $row
     * @return array{attributes: array<string, mixed>, errors: array<int, string>}
     */
    public static function fromSheetRow(array $row): array
    {
        $attributes = [];
        $errors = [];
        $index = 0;

        foreach (self::columns() as $dbColumn => $definition) {
            $rawValue = $row[$index] ?? null;
            $index++;

            if (! $definition['editable']) {
                continue;
            }

            $value = is_string($rawValue) ? trim($rawValue) : $rawValue;

            if ($value === null || $value === '') {
                $attributes[$dbColumn] = null;

                continue;
            }

            $parsed = self::parseFromSheet((string) $value, $definition);

            // null means unparseable — empty cells were already handled above,
            // so no legitimate value reaches this point as null.
            if ($parsed === null) {
                $errors[] = "{$dbColumn}: nilai \"{$value}\" tidak valid";

                continue;
            }

            $attributes[$dbColumn] = $parsed;
        }

        return ['attributes' => $attributes, 'errors' => $errors];
    }

    /**
     * Fingerprint of a row's editable values. Both directions hash the same
     * normalised shape, so an unchanged round trip produces an identical hash.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function hash(array $attributes): string
    {
        $normalised = [];

        foreach (self::columns() as $dbColumn => $definition) {
            if (! $definition['editable']) {
                continue;
            }

            $normalised[$dbColumn] = self::formatForSheet(
                $attributes[$dbColumn] ?? null,
                $definition['type'],
                withTextPrefix: false
            );
        }

        return hash('sha256', (string) json_encode($normalised));
    }

    public static function hashModel(DetailSantri $santri): string
    {
        return self::hash($santri->only(self::editableDbColumns()));
    }

    /**
     * @param  array<int, string|null>  $row
     */
    public static function hashSheetRow(array $row): string
    {
        return self::hash(self::fromSheetRow($row)['attributes']);
    }

    private static function formatForSheet(mixed $value, string $type, bool $withTextPrefix = true): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $formatted = match ($type) {
            'bool' => $value ? 'Ya' : 'Tidak',
            'date' => CarbonImmutable::parse((string) $value)->format('Y-m-d'),
            'int', 'year' => (string) (int) $value,
            default => (string) $value,
        };

        if ($withTextPrefix && in_array($type, self::TEXT_TYPES, true)) {
            return "'".$formatted;
        }

        return $formatted;
    }

    /**
     * @param  array{label: string, type: string, editable: bool, values?: array<int, string>}  $definition
     * @return mixed|null null when the value cannot be parsed
     */
    private static function parseFromSheet(string $value, array $definition): mixed
    {
        // Sheets sometimes hands back the literal apostrophe used to force text.
        $value = ltrim($value, "'");

        return match ($definition['type']) {
            'bool' => self::parseBool($value),
            'date' => self::parseDate($value),
            'int' => self::parseInt($value),
            'year' => self::parseYear($value),
            'enum' => self::parseEnum($value, $definition['values'] ?? []),
            default => $value,
        };
    }

    private static function parseBool(string $value): ?bool
    {
        return match (Str::lower($value)) {
            'ya', 'yes', 'true', '1', 'y', 'sudah' => true,
            'tidak', 'no', 'false', '0', 'n', 'belum' => false,
            default => null,
        };
    }

    private static function parseDate(string $value): ?string
    {
        try {
            return CarbonImmutable::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Tolerates the thousands separators and currency prefixes an admin will
     * naturally type into a Penghasilan cell, e.g. "Rp 5.000.000".
     */
    private static function parseInt(string $value): ?int
    {
        $digits = preg_replace('/[^0-9]/', '', $value) ?? '';

        return $digits === '' ? null : (int) $digits;
    }

    private static function parseYear(string $value): ?int
    {
        return ctype_digit($value) && \strlen($value) === 4 ? (int) $value : null;
    }

    /**
     * @param  array<int, string>  $allowed
     */
    private static function parseEnum(string $value, array $allowed): ?string
    {
        foreach ($allowed as $candidate) {
            if (Str::lower($candidate) === Str::lower($value)) {
                return $candidate;
            }
        }

        return null;
    }
}
