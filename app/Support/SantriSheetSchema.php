<?php

namespace App\Support;

use App\Models\DetailSantri;

class SantriSheetSchema
{
    /** @return array<string, array{label: string, type: string, editable: bool, values?: array<int, string>, min?: int, max?: int}> */
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
            'anak_ke' => [
                'label' => 'Anak Ke',
                'type' => 'int',
                'editable' => true,
                'min' => 0,
                'max' => DetailSantri::MAX_ANAK_KE,
            ],
            'jumlah_saudara' => [
                'label' => 'Jumlah Saudara',
                'type' => 'int',
                'editable' => true,
                'min' => 0,
                'max' => DetailSantri::MAX_JUMLAH_SAUDARA,
            ],
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

    /** @return array<int, string> */
    public static function editableDbColumns(): array
    {
        return array_keys(array_filter(self::columns(), fn (array $column): bool => $column['editable']));
    }

    /** @return array<string, mixed> */
    public static function values(DetailSantri $santri): array
    {
        $values = [];

        foreach (self::columns() as $field => $column) {
            $value = $santri->getAttribute($field);
            $values[$field] = $value === null ? null : match ($column['type']) {
                'bool' => (bool) $value,
                'int', 'year' => (int) $value,
                default => (string) $value,
            };
        }

        return $values;
    }

    public static function revision(DetailSantri $santri): string
    {
        return hash('sha256', json_encode(self::values($santri), JSON_THROW_ON_ERROR));
    }
}
