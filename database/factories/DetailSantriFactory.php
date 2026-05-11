<?php

namespace Database\Factories;

use App\Models\DetailSantri;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DetailSantri>
 */
class DetailSantriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('id_ID');
        $tahunMasukSekolah = $faker->numberBetween(2015, 2022);
        $tahunMasukPpm = $faker->numberBetween(max($tahunMasukSekolah, 2018), 2025);

        return [
            'user_id' => User::factory(),
            'nama_lengkap' => fn (array $attributes): string => User::query()->findOrFail($attributes['user_id'])->name,
            'nama_panggilan' => fn (array $attributes): string => (string) Str::of(User::query()->findOrFail($attributes['user_id'])->name)->before(' '),
            'NISN' => $faker->unique()->numerify('##########'),
            'NIK' => $faker->unique()->numerify('################'),
            'tanggal_lahir' => $faker->dateTimeBetween('-18 years', '-12 years')->format('Y-m-d'),
            'tempat_lahir' => $faker->city(),
            'status_anak' => $faker->randomElement(['Lengkap', 'Yatim', 'Piatu', 'Yatim Piatu']),
            'anak_ke' => $faker->numberBetween(1, 5),
            'jumlah_saudara' => $faker->numberBetween(1, 6),
            'hobi' => implode(', ', $faker->randomElements(['Membaca', 'Olahraga', 'Menulis', 'Musik', 'Memasak'], 2)),
            'cita-cita' => $faker->randomElement(['Guru', 'Dokter', 'Programmer', 'Pengusaha', 'Dosen']),
            'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
            'tinggi_badan' => $faker->numberBetween(145, 180),
            'berat_badan' => $faker->numberBetween(40, 80),
            'golongan_darah' => $faker->randomElement(['A', 'B', 'AB', 'O']),
            'riwayat_penyakit' => $faker->boolean(80) ? 'Tidak ada' : $faker->randomElement(['Asma ringan', 'Alergi debu', 'Maag']),
            'status_bpjs' => $faker->randomElement(['Tidak Memiliki', 'Memiliki', 'Memiliki KIS']),
            'status_pip' => $faker->randomElement(['Tidak Memiliki', 'Memiliki']),
            'ijazah_terakhir' => $faker->randomElement(['SD', 'SMP', 'SMA', 'Paket C']),
            'nama_sekolah_asal' => 'Sekolah ' . $faker->city(),
            'prodi_sekolah_asal' => $faker->randomElement(['IPA', 'IPS', 'Bahasa', 'Teknik Informatika', 'Manajemen']),
            'tahun_masuk_sekolah' => $tahunMasukSekolah,
            'tahun_masuk_ppm' => $tahunMasukPpm,
            'is_mondok' => $faker->boolean(),
            'khatam' => $faker->randomElement(['Bukhori', 'Nasai', 'Muslim', 'Tirmidzi', 'Abu Daud', 'Ibnu Majah']),
            'asalkelompoksambung' => 'Kelompok ' . $faker->randomElement(['Tahfidz', 'Kitab', 'Bahasa']),
            'desa' => $faker->streetName(),
            'alamat' => $faker->streetAddress(),
            'rtrw' => sprintf('%02d/%02d', $faker->numberBetween(1, 15), $faker->numberBetween(1, 15)),
            'provinsi' => $faker->state(),
            'kabupaten' => $faker->city(),
            'kecamatan' => $faker->city(),
            'kodepos' => $faker->postcode(),
            'no_hp' => $faker->numerify('08##########'),
            'email' => fn (array $attributes): string => User::query()->findOrFail($attributes['user_id'])->email,
            'media_sosial' => fn (array $attributes): string => '@' . Str::slug(User::query()->findOrFail($attributes['user_id'])->name) . $this->faker->numberBetween(1, 99),
            'is_motor' => $faker->boolean(),
            'is_sepeda' => $faker->boolean(),
            'is_laptop' => $faker->boolean(),
            'sim' => $faker->boolean(30) ? $faker->randomElement(['SIM A', 'SIM C']) : null,
            'image_ktp_path' => null,
            'image_pasfoto_path' => null,
            'no_kk' => $faker->unique()->numerify('################'),
            'nama_ayah' => $faker->name('male'),
            'nik_ayah' => $faker->unique()->numerify('################'),
            'tempat_lahir_ayah' => $faker->city(),
            'tanggal_lahir_ayah' => $faker->dateTimeBetween('-60 years', '-35 years')->format('Y-m-d'),
            'pendidikan_ayah' => $faker->randomElement(['SD', 'SMP', 'SMA', 'Diploma', 'S1']),
            'pekerjaan_ayah' => $faker->jobTitle(),
            'penghasilan_ayah' => $faker->numberBetween(2000000, 8000000),
            'no_hp_ayah' => $faker->numerify('08##########'),
            'nama_ibu' => $faker->name('female'),
            'nik_ibu' => $faker->unique()->numerify('################'),
            'tempat_lahir_ibu' => $faker->city(),
            'tanggal_lahir_ibu' => $faker->dateTimeBetween('-55 years', '-30 years')->format('Y-m-d'),
            'pendidikan_ibu' => $faker->randomElement(['SD', 'SMP', 'SMA', 'Diploma', 'S1']),
            'pekerjaan_ibu' => $faker->jobTitle(),
            'penghasilan_ibu' => $faker->numberBetween(1500000, 6000000),
            'no_hp_ibu' => $faker->numerify('08##########'),
            'is_ayah_alive' => $faker->boolean(90),
            'is_ibu_alive' => $faker->boolean(95),
        ];
    }
}
