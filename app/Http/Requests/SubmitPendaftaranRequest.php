<?php

namespace App\Http\Requests;

use App\Models\DetailSantri;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitPendaftaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $yearUpperBound = now()->year + 1;

        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['nullable', 'string', 'max:255'],
            'status_anak' => ['nullable', Rule::in(['Lengkap', 'Yatim', 'Piatu', 'Yatim Piatu'])],
            'anak_ke' => ['nullable', 'integer', 'min:0', 'max:'.DetailSantri::MAX_ANAK_KE],
            'NISN' => ['nullable', 'string', 'max:20'],
            'jumlah_saudara' => ['nullable', 'integer', 'min:0', 'max:'.DetailSantri::MAX_JUMLAH_SAUDARA],
            'NIK' => ['nullable', 'string', 'max:16'],
            'hobi' => ['nullable', 'string', 'max:500'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'cita-cita' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'tinggi_badan' => ['nullable', 'integer', 'min:0'],
            'status_bpjs' => ['nullable', Rule::in(['Tidak Memiliki', 'Memiliki', 'Memiliki KIS'])],
            'berat_badan' => ['nullable', 'integer', 'min:0'],
            'status_pip' => ['nullable', Rule::in(['Tidak Memiliki', 'Memiliki'])],
            'golongan_darah' => ['nullable', 'string', 'max:3'],
            'riwayat_penyakit' => ['nullable', 'string', 'max:255'],
            'ijazah_terakhir' => ['nullable', Rule::in(['SD', 'SMP', 'SMA'])],
            'is_mondok' => ['nullable', 'boolean'],
            'nama_sekolah_asal' => ['nullable', 'string', 'max:255'],
            'khatam' => ['nullable', 'array'],
            'khatam.*' => ['required', Rule::in(['Tidak Ada', 'Bukhori', 'Nasai', 'Muslim', 'Tirmidzi', 'Abu Daud', 'Ibnu Majah'])],
            'prodi_sekolah_asal' => ['nullable', 'string', 'max:255'],
            'tahun_masuk_sekolah' => ['nullable', 'integer', 'min:1900', 'max:'.$yearUpperBound],
            'tahun_masuk_ppm' => ['nullable', 'integer', 'min:1900', 'max:'.$yearUpperBound],
            'asalkelompoksambung' => ['nullable', 'string', 'max:255'],
            'desa' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rtrw' => ['nullable', 'string', 'max:20'],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'kabupaten' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'kodepos' => ['nullable', 'string', 'max:10'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'media_sosial' => ['nullable', 'string', 'max:255'],
            'is_motor' => ['nullable', 'boolean'],
            'is_sepeda' => ['nullable', 'boolean'],
            'is_laptop' => ['nullable', 'boolean'],
            'sim' => ['nullable', Rule::in(['SIM A', 'SIM C', 'Tidak Punya'])],
            'image_ktp_path' => ['nullable', 'image', 'max:4096'],
            'image_pasfoto_path' => ['nullable', 'image', 'max:4096'],
            'no_kk' => ['nullable', 'string', 'max:16'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'pendidikan_ayah' => ['nullable', 'string', 'max:255'],
            'nik_ayah' => ['nullable', 'string', 'max:16'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:255'],
            'tempat_lahir_ayah' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir_ayah' => ['nullable', 'date'],
            'penghasilan_ayah' => ['nullable', 'integer', 'min:0'],
            'no_hp_ayah' => ['nullable', 'string', 'max:20'],
            'is_ayah_alive' => ['nullable', 'boolean'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'pendidikan_ibu' => ['nullable', 'string', 'max:255'],
            'nik_ibu' => ['nullable', 'string', 'max:16'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:255'],
            'tempat_lahir_ibu' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir_ibu' => ['nullable', 'date'],
            'penghasilan_ibu' => ['nullable', 'integer', 'min:0'],
            'no_hp_ibu' => ['nullable', 'string', 'max:20'],
            'is_ibu_alive' => ['nullable', 'boolean'],
        ];
    }
}
