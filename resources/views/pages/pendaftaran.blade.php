
@extends('layouts.app')

@section('content')
    @php
        $detail = $detailSantri ?? null;
        $steps = [
            ['title' => 'Data Diri Santri', 'subtitle' => 'Lengkapi identitas diri santri sesuai dokumen resmi.'],
            ['title' => 'Data Kesehatan', 'subtitle' => 'Informasi kesehatan diperlukan untuk penanganan darurat.'],
            ['title' => 'Pendidikan', 'subtitle' => 'Catat riwayat pendidikan formal dan non-formal santri.'],
            ['title' => 'Kontak & Alamat', 'subtitle' => 'Pastikan data alamat dan kontak aktif untuk keperluan komunikasi.'],
            ['title' => 'Dokumen', 'subtitle' => 'Unggah dokumen wajib dan data kepemilikan aset pribadi.'],
            ['title' => 'Data Keluarga - Ayah', 'subtitle' => 'Isikan informasi ayah atau wali dengan lengkap.'],
            ['title' => 'Data Keluarga - Ibu', 'subtitle' => 'Isikan informasi ibu atau wali dengan lengkap.'],
        ];
        $inputClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200';
        $textareaClass = $inputClass . ' min-h-[140px] resize-y';
        $labelClass = 'text-sm font-medium text-slate-700';
        $stringValue = fn (string $name, $fallback = null) => old($name, optional($detail)->getAttribute($name) ?? $fallback);
        $arrayValue = function (string $name) use ($detail) {
            $value = old($name, optional($detail)->getAttribute($name));

            if (is_array($value)) {
                return $value;
            }

            if (blank($value)) {
                return [];
            }

            return collect(explode(',', (string) $value))
                ->map(fn ($item) => trim($item))
                ->filter()
                ->values()
                ->all();
        };
        $booleanValue = function (string $name) use ($detail) {
            $value = old($name, optional($detail)->getAttribute($name));
            if ($value === null || $value === '') {
                return null;
            }

            $truthy = ['1', 1, true, 'true', 'on', 'yes'];

            return in_array($value, $truthy, true) ? '1' : '0';
        };
    @endphp

    <div class="bg-slate-100 py-12">
        <div class="mx-auto max-w-6xl px-4">
            <div class="mb-8">
                <nav class="mb-3 text-sm text-slate-600">
                    <ol class="flex items-center gap-2">
                        <li>
                            <a href="{{ route('home') }}" class="font-medium text-emerald-600 transition hover:text-emerald-700">Santri</a>
                        </li>
                        <li class="text-slate-400">/</li>
                        <li class="font-medium text-slate-700">Form Pendaftaran</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-semibold text-slate-900">Formulir Pendaftaran Santri</h1>
                <p class="mt-2 text-sm text-slate-600">Form ini memuat seluruh informasi yang dibutuhkan untuk proses administrasi santri.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-700">
                    <div class="font-semibold">Periksa kembali data berikut:</div>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="pendaftaran-wrapper"
                 data-step-titles='@json(array_column($steps, "title"))'
                 data-step-subtitles='@json(array_column($steps, "subtitle"))'
                 class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-8 py-6">
                    <div class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Pendaftaran Santri</div>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900" data-step-title>{{ $steps[0]['title'] }}</h2>
                    <p class="mt-1 text-sm text-slate-600" data-step-subtitle>{{ $steps[0]['subtitle'] }}</p>
                    <p class="mt-3 text-xs font-semibold uppercase tracking-widest text-slate-400" data-step-progress>
                        Langkah 1 dari {{ count($steps) }}
                    </p>
                </div>

                <form method="POST" action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data" class="px-8 py-8">
                    @csrf

                    <div class="space-y-10">
                        <section data-step="0" class="space-y-8">
                            @php
                                $statusAnak = $stringValue('status_anak');
                                $jenisKelamin = $stringValue('jenis_kelamin');
                            @endphp
                            <div class="rounded-3xl bg-slate-50 p-6 shadow-inner">
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="nama_lengkap" class="{{ $labelClass }}">Nama Lengkap <span class="text-rose-500">*</span></label>
                                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ $stringValue('nama_lengkap') }}" class="{{ $inputClass }}" required autocomplete="name">
                                    </div>
                                    <div>
                                        <label for="nama_panggilan" class="{{ $labelClass }}">Nama Panggilan</label>
                                        <input type="text" id="nama_panggilan" name="nama_panggilan" value="{{ $stringValue('nama_panggilan') }}" class="{{ $inputClass }}" autocomplete="nickname">
                                    </div>
                                    <div>
                                        <label for="NISN" class="{{ $labelClass }}">NISN</label>
                                        <input type="text" id="NISN" name="NISN" value="{{ $stringValue('NISN') }}" class="{{ $inputClass }}" autocomplete="off">
                                    </div>
                                    <div>
                                        <label for="NIK" class="{{ $labelClass }}">NIK</label>
                                        <input type="text" id="NIK" name="NIK" value="{{ $stringValue('NIK') }}" class="{{ $inputClass }}" autocomplete="off">
                                    </div>
                                    <div>
                                        <label for="tempat_lahir" class="{{ $labelClass }}">Tempat Lahir</label>
                                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ $stringValue('tempat_lahir') }}" class="{{ $inputClass }}" autocomplete="off">
                                    </div>
                                    <div>
                                        <label for="tanggal_lahir" class="{{ $labelClass }}">Tanggal Lahir</label>
                                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ $stringValue('tanggal_lahir') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="anak_ke" class="{{ $labelClass }}">Anak Ke</label>
                                        <input type="number" id="anak_ke" name="anak_ke" value="{{ $stringValue('anak_ke') }}" min="0" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="jumlah_saudara" class="{{ $labelClass }}">Jumlah Saudara</label>
                                        <input type="number" id="jumlah_saudara" name="jumlah_saudara" value="{{ $stringValue('jumlah_saudara') }}" min="0" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="hobi" class="{{ $labelClass }}">Hobi</label>
                                        <input type="text" id="hobi" name="hobi" value="{{ $stringValue('hobi') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="cita-cita" class="{{ $labelClass }}">Cita-cita</label>
                                        <input type="text" id="cita-cita" name="cita-cita" value="{{ $stringValue('cita-cita') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="{{ $labelClass }}">Status Anak</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            @foreach (['Lengkap', 'Yatim', 'Piatu', 'Yatim Piatu'] as $option)
                                                <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                    <input type="radio" name="status_anak" value="{{ $option }}" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $statusAnak === $option ? 'checked' : '' }}>
                                                    <span>{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="{{ $labelClass }}">Jenis Kelamin</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            @foreach (['Laki-laki', 'Perempuan'] as $option)
                                                <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                    <input type="radio" name="jenis_kelamin" value="{{ $option }}" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $jenisKelamin === $option ? 'checked' : '' }}>
                                                    <span>{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section data-step="1" class="hidden space-y-8">
                            @php
                                $statusBpjs = $stringValue('status_bpjs');
                                $statusPip = $stringValue('status_pip');
                            @endphp
                            <div class="rounded-3xl bg-slate-50 p-6 shadow-inner">
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="tinggi_badan" class="{{ $labelClass }}">Tinggi Badan (cm)</label>
                                        <input type="number" id="tinggi_badan" name="tinggi_badan" value="{{ $stringValue('tinggi_badan') }}" min="0" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="berat_badan" class="{{ $labelClass }}">Berat Badan (kg)</label>
                                        <input type="number" id="berat_badan" name="berat_badan" value="{{ $stringValue('berat_badan') }}" min="0" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="golongan_darah" class="{{ $labelClass }}">Golongan Darah</label>
                                        <input type="text" id="golongan_darah" name="golongan_darah" value="{{ $stringValue('golongan_darah') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="riwayat_penyakit" class="{{ $labelClass }}">Riwayat Penyakit</label>
                                        <input type="text" id="riwayat_penyakit" name="riwayat_penyakit" value="{{ $stringValue('riwayat_penyakit') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="{{ $labelClass }}">Kepemilikan BPJS</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            @foreach (['Tidak Memiliki', 'Memiliki', 'Memiliki KIS'] as $option)
                                                <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                    <input type="radio" name="status_bpjs" value="{{ $option }}" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $statusBpjs === $option ? 'checked' : '' }}>
                                                    <span>{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="{{ $labelClass }}">Kepemilikan PIP</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            @foreach (['Tidak Memiliki', 'Memiliki'] as $option)
                                                <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                    <input type="radio" name="status_pip" value="{{ $option }}" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $statusPip === $option ? 'checked' : '' }}>
                                                    <span>{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section data-step="2" class="hidden space-y-8">
                            @php
                                $isMondok = $booleanValue('is_mondok');
                                $khatam = $arrayValue('khatam');
                                $ijazahTerakhir = $stringValue('ijazah_terakhir');
                            @endphp
                            <div class="rounded-3xl bg-slate-50 p-6 shadow-inner">
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="ijazah_terakhir" class="{{ $labelClass }}">Ijazah Terakhir</label>
                                        <select id="ijazah_terakhir" name="ijazah_terakhir" class="{{ $inputClass }}">
                                            <option value="">Pilih jenjang terakhir</option>
                                            @foreach (['SD', 'SMP', 'SMA'] as $option)
                                                <option value="{{ $option }}" {{ $ijazahTerakhir === $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="nama_sekolah_asal" class="{{ $labelClass }}">Sekolah / Universitas</label>
                                        <input type="text" id="nama_sekolah_asal" name="nama_sekolah_asal" value="{{ $stringValue('nama_sekolah_asal') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="prodi_sekolah_asal" class="{{ $labelClass }}">Program Studi / Jurusan</label>
                                        <input type="text" id="prodi_sekolah_asal" name="prodi_sekolah_asal" value="{{ $stringValue('prodi_sekolah_asal') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="tahun_masuk_sekolah" class="{{ $labelClass }}">Tahun Masuk Sekolah</label>
                                        <input type="number" id="tahun_masuk_sekolah" name="tahun_masuk_sekolah" value="{{ $stringValue('tahun_masuk_sekolah') }}" min="1900" max="{{ now()->year + 1 }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="tahun_masuk_ppm" class="{{ $labelClass }}">Tahun Masuk PPM</label>
                                        <input type="number" id="tahun_masuk_ppm" name="tahun_masuk_ppm" value="{{ $stringValue('tahun_masuk_ppm') }}" min="1900" max="{{ now()->year + 1 }}" class="{{ $inputClass }}">
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="{{ $labelClass }}">Apakah sudah pernah mondok?</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_mondok" value="0" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isMondok === '0' ? 'checked' : '' }}>
                                                <span>Belum Pernah</span>
                                            </label>
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_mondok" value="1" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isMondok === '1' ? 'checked' : '' }}>
                                                <span>Sudah Pernah</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="{{ $labelClass }}">Khatam Hadist Besar</span>
                                        <div class="mt-3 grid gap-3 md:grid-cols-3">
                                            @foreach (['Tidak Ada', 'Bukhori', 'Nasai', 'Muslim', 'Tirmidzi', 'Abu Daud', 'Ibnu Majah'] as $option)
                                                <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                    <input type="checkbox" name="khatam[]" value="{{ $option }}" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" {{ in_array($option, $khatam, true) ? 'checked' : '' }}>
                                                    <span>{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section data-step="3" class="hidden space-y-8">
                            <div class="rounded-3xl bg-slate-50 p-6 shadow-inner">
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="asalkelompoksambung" class="{{ $labelClass }}">Asal Kelompok Sambung</label>
                                        <input type="text" id="asalkelompoksambung" name="asalkelompoksambung" value="{{ $stringValue('asalkelompoksambung') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="kabupaten" class="{{ $labelClass }}">Kabupaten / Kota</label>
                                        <input type="text" id="kabupaten" name="kabupaten" value="{{ $stringValue('kabupaten') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="desa" class="{{ $labelClass }}">Desa</label>
                                        <input type="text" id="desa" name="desa" value="{{ $stringValue('desa') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="kecamatan" class="{{ $labelClass }}">Kecamatan</label>
                                        <input type="text" id="kecamatan" name="kecamatan" value="{{ $stringValue('kecamatan') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="alamat" class="{{ $labelClass }}">Alamat Lengkap</label>
                                        <textarea id="alamat" name="alamat" class="{{ $textareaClass }}">{{ $stringValue('alamat') }}</textarea>
                                    </div>
                                    <div>
                                        <label for="kodepos" class="{{ $labelClass }}">Kode Pos</label>
                                        <input type="text" id="kodepos" name="kodepos" value="{{ $stringValue('kodepos') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="rtrw" class="{{ $labelClass }}">RT / RW</label>
                                        <input type="text" id="rtrw" name="rtrw" value="{{ $stringValue('rtrw') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="provinsi" class="{{ $labelClass }}">Provinsi</label>
                                        <input type="text" id="provinsi" name="provinsi" value="{{ $stringValue('provinsi') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="no_hp" class="{{ $labelClass }}">No HP (Aktif / WhatsApp)</label>
                                        <input type="text" id="no_hp" name="no_hp" value="{{ $stringValue('no_hp') }}" class="{{ $inputClass }}" autocomplete="tel">
                                    </div>
                                    <div>
                                        <label for="email" class="{{ $labelClass }}">Email</label>
                                        <input type="email" id="email" name="email" value="{{ $stringValue('email') }}" class="{{ $inputClass }}" autocomplete="email">
                                    </div>
                                    <div>
                                        <label for="media_sosial" class="{{ $labelClass }}">Media Sosial</label>
                                        <input type="text" id="media_sosial" name="media_sosial" value="{{ $stringValue('media_sosial') }}" class="{{ $inputClass }}">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section data-step="4" class="hidden space-y-8">
                            @php
                                $isMotor = $booleanValue('is_motor');
                                $isSepeda = $booleanValue('is_sepeda');
                                $isLaptop = $booleanValue('is_laptop');
                                $simStatus = $stringValue('sim');
                            @endphp
                            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 shadow-inner">
                                <span class="inline-flex rounded-full border border-emerald-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-700">
                                    Dokumen
                                </span>
                                <h3 class="mt-4 text-lg font-semibold text-slate-900">Unduh Surat Pernyataan</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600">Silakan unduh berkas surat pernyataan pendaftaran santri.</p>
                                <a href="{{ asset('SURAT PERNYATAAN SANTRI BARU 2026.pdf') }}" download="SURAT PERNYATAAN SANTRI BARU 2026.pdf" class="mt-5 inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h4a1 1 0 110 2H5v12h10V4h-3a1 1 0 110-2h4a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V3zm6 2a1 1 0 10-2 0v6.586L6.707 10.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L9 11.586V5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Unduh File
                                </a>
                            </div>
                            <div class="rounded-3xl bg-slate-50 p-6 shadow-inner">
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <span class="{{ $labelClass }}">Apakah membawa motor ke PPM?</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_motor" value="0" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isMotor === '0' ? 'checked' : '' }}>
                                                <span>Tidak Memiliki</span>
                                            </label>
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_motor" value="1" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isMotor === '1' ? 'checked' : '' }}>
                                                <span>Memiliki</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="{{ $labelClass }}">Apakah membawa sepeda ke PPM?</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_sepeda" value="0" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isSepeda === '0' ? 'checked' : '' }}>
                                                <span>Tidak Memiliki</span>
                                            </label>
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_sepeda" value="1" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isSepeda === '1' ? 'checked' : '' }}>
                                                <span>Memiliki</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="{{ $labelClass }}">Apakah membawa laptop ke PPM?</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_laptop" value="0" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isLaptop === '0' ? 'checked' : '' }}>
                                                <span>Tidak Memiliki</span>
                                            </label>
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_laptop" value="1" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isLaptop === '1' ? 'checked' : '' }}>
                                                <span>Memiliki</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="sim" class="{{ $labelClass }}">Kepemilikan SIM</label>
                                        <select id="sim" name="sim" class="{{ $inputClass }}">
                                            <option value="">Pilih salah satu</option>
                                            @foreach (['SIM A', 'SIM C', 'Tidak Punya'] as $option)
                                                <option value="{{ $option }}" {{ $simStatus === $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="image_ktp_path" class="{{ $labelClass }}">Foto KTP</label>
                                        <input type="file" id="image_ktp_path" name="image_ktp_path" accept="image/*" class="w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:font-medium file:text-white hover:file:bg-emerald-700">
                                        @if ($detail && $detail->image_ktp_path)
                                            <p class="mt-2 text-xs text-slate-500">
                                                Berkas saat ini:
                                                <a href="{{ Storage::disk('public')->url($detail->image_ktp_path) }}" target="_blank" rel="noopener" class="font-semibold text-emerald-600 hover:text-emerald-700">
                                                    Lihat file
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="image_pasfoto_path" class="{{ $labelClass }}">Pas Foto</label>
                                        <input type="file" id="image_pasfoto_path" name="image_pasfoto_path" accept="image/*" class="w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:font-medium file:text-white hover:file:bg-emerald-700">
                                        @if ($detail && $detail->image_pasfoto_path)
                                            <p class="mt-2 text-xs text-slate-500">
                                                Berkas saat ini:
                                                <a href="{{ Storage::disk('public')->url($detail->image_pasfoto_path) }}" target="_blank" rel="noopener" class="font-semibold text-emerald-600 hover:text-emerald-700">
                                                    Lihat file
                                                </a>
                                            </p>
                                        @endif
                                        <p class="mt-3 text-xs leading-relaxed text-slate-500">
                                            • Putra: baju putih polos.
                                            • Putri: kerudung krem polos.
                                            • Latar belakang polos (abu atau putih cream).
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section data-step="5" class="hidden space-y-8">
                            @php
                                $isAyahAlive = $booleanValue('is_ayah_alive');
                            @endphp
                            <div class="rounded-3xl bg-slate-50 p-6 shadow-inner">
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div class="md:col-span-2">
                                        <label for="no_kk" class="{{ $labelClass }}">Nomor KK</label>
                                        <input type="text" id="no_kk" name="no_kk" value="{{ $stringValue('no_kk') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="nama_ayah" class="{{ $labelClass }}">Nama Ayah</label>
                                        <input type="text" id="nama_ayah" name="nama_ayah" value="{{ $stringValue('nama_ayah') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="pendidikan_ayah" class="{{ $labelClass }}">Pendidikan Terakhir Ayah</label>
                                        <input type="text" id="pendidikan_ayah" name="pendidikan_ayah" value="{{ $stringValue('pendidikan_ayah') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="nik_ayah" class="{{ $labelClass }}">NIK Ayah</label>
                                        <input type="text" id="nik_ayah" name="nik_ayah" value="{{ $stringValue('nik_ayah') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="pekerjaan_ayah" class="{{ $labelClass }}">Pekerjaan Ayah</label>
                                        <input type="text" id="pekerjaan_ayah" name="pekerjaan_ayah" value="{{ $stringValue('pekerjaan_ayah') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="tempat_lahir_ayah" class="{{ $labelClass }}">Tempat Lahir Ayah</label>
                                        <input type="text" id="tempat_lahir_ayah" name="tempat_lahir_ayah" value="{{ $stringValue('tempat_lahir_ayah') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="tanggal_lahir_ayah" class="{{ $labelClass }}">Tanggal Lahir Ayah</label>
                                        <input type="date" id="tanggal_lahir_ayah" name="tanggal_lahir_ayah" value="{{ $stringValue('tanggal_lahir_ayah') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="penghasilan_ayah" class="{{ $labelClass }}">Rata-rata Penghasilan / bln</label>
                                        <input type="number" id="penghasilan_ayah" name="penghasilan_ayah" value="{{ $stringValue('penghasilan_ayah') }}" min="0" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="no_hp_ayah" class="{{ $labelClass }}">No HP Ayah (Aktif / WhatsApp)</label>
                                        <input type="text" id="no_hp_ayah" name="no_hp_ayah" value="{{ $stringValue('no_hp_ayah') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="{{ $labelClass }}">Status Ayah</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_ayah_alive" value="1" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isAyahAlive === '1' ? 'checked' : '' }}>
                                                <span>Hidup</span>
                                            </label>
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_ayah_alive" value="0" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isAyahAlive === '0' ? 'checked' : '' }}>
                                                <span>Meninggal</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section data-step="6" class="hidden space-y-8">
                            @php
                                $isIbuAlive = $booleanValue('is_ibu_alive');
                            @endphp
                            <div class="rounded-3xl bg-slate-50 p-6 shadow-inner">
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="nama_ibu" class="{{ $labelClass }}">Nama Ibu</label>
                                        <input type="text" id="nama_ibu" name="nama_ibu" value="{{ $stringValue('nama_ibu') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="pendidikan_ibu" class="{{ $labelClass }}">Pendidikan Terakhir Ibu</label>
                                        <input type="text" id="pendidikan_ibu" name="pendidikan_ibu" value="{{ $stringValue('pendidikan_ibu') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="nik_ibu" class="{{ $labelClass }}">NIK Ibu</label>
                                        <input type="text" id="nik_ibu" name="nik_ibu" value="{{ $stringValue('nik_ibu') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="pekerjaan_ibu" class="{{ $labelClass }}">Pekerjaan Ibu</label>
                                        <input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" value="{{ $stringValue('pekerjaan_ibu') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="tempat_lahir_ibu" class="{{ $labelClass }}">Tempat Lahir Ibu</label>
                                        <input type="text" id="tempat_lahir_ibu" name="tempat_lahir_ibu" value="{{ $stringValue('tempat_lahir_ibu') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="tanggal_lahir_ibu" class="{{ $labelClass }}">Tanggal Lahir Ibu</label>
                                        <input type="date" id="tanggal_lahir_ibu" name="tanggal_lahir_ibu" value="{{ $stringValue('tanggal_lahir_ibu') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="penghasilan_ibu" class="{{ $labelClass }}">Rata-rata Penghasilan / bln</label>
                                        <input type="number" id="penghasilan_ibu" name="penghasilan_ibu" value="{{ $stringValue('penghasilan_ibu') }}" min="0" class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label for="no_hp_ibu" class="{{ $labelClass }}">No HP Ibu (Aktif / WhatsApp)</label>
                                        <input type="text" id="no_hp_ibu" name="no_hp_ibu" value="{{ $stringValue('no_hp_ibu') }}" class="{{ $inputClass }}">
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="{{ $labelClass }}">Status Ibu</span>
                                        <div class="mt-3 flex flex-wrap gap-4">
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_ibu_alive" value="1" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isIbuAlive === '1' ? 'checked' : '' }}>
                                                <span>Hidup</span>
                                            </label>
                                            <label class="flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                                <input type="radio" name="is_ibu_alive" value="0" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500" {{ $isIbuAlive === '0' ? 'checked' : '' }}>
                                                <span>Meninggal</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="mt-10 border-t border-slate-200 pt-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="flex justify-center gap-2 md:justify-start">
                                @foreach ($steps as $index => $step)
                                    <button type="button"
                                            data-step-indicator="{{ $index }}"
                                            class="step-indicator flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-sm font-semibold text-slate-400 transition duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                                        {{ $index + 1 }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <button type="button"
                                        data-action="prev"
                                        class="hidden rounded-full border border-slate-300 px-5 py-2 text-sm font-medium text-slate-600 transition hover:border-emerald-500 hover:text-emerald-600">
                                    Sebelumnya
                                </button>
                                <button type="button"
                                        data-action="next"
                                        class="rounded-full bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                                    Langkah Berikutnya
                                </button>
                                <button type="submit"
                                        data-action="submit"
                                        class="hidden rounded-full bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                                    Simpan Formulir
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('pendaftaran-wrapper');
            if (!wrapper) {
                return;
            }

            const titles = JSON.parse(wrapper.dataset.stepTitles || '[]');
            const subtitles = JSON.parse(wrapper.dataset.stepSubtitles || '[]');

            const panels = Array.from(wrapper.querySelectorAll('[data-step]'));
            const titleEl = wrapper.querySelector('[data-step-title]');
            const subtitleEl = wrapper.querySelector('[data-step-subtitle]');
            const progressEl = wrapper.querySelector('[data-step-progress]');
            const prevBtn = wrapper.querySelector('[data-action="prev"]');
            const nextBtn = wrapper.querySelector('[data-action="next"]');
            const submitBtn = wrapper.querySelector('[data-action="submit"]');
            const indicators = Array.from(wrapper.querySelectorAll('[data-step-indicator]'));

            let currentStep = 0;

            const totalSteps = panels.length;

            const indicatorStates = {
                active: ['bg-emerald-600', 'text-white', 'border-emerald-600'],
                done: ['bg-emerald-100', 'text-emerald-700', 'border-emerald-300'],
                idle: ['bg-white', 'text-slate-400', 'border-slate-300'],
            };

            const setIndicatorState = (indicator, state) => {
                Object.values(indicatorStates).forEach(classes => indicator.classList.remove(...classes));
                indicator.classList.add(...indicatorStates[state]);
                indicator.setAttribute('aria-current', state === 'active' ? 'step' : 'false');
            };

            const updateUI = (shouldScroll = false) => {
                panels.forEach((panel, index) => {
                    panel.classList.toggle('hidden', index !== currentStep);
                });

                if (titleEl && titles[currentStep]) {
                    titleEl.textContent = titles[currentStep];
                }

                if (subtitleEl) {
                    subtitleEl.textContent = subtitles[currentStep] || '';
                }

                if (progressEl) {
                    progressEl.textContent = `Langkah ${currentStep + 1} dari ${totalSteps}`;
                }

                if (prevBtn) {
                    prevBtn.classList.toggle('hidden', currentStep === 0);
                }

                if (nextBtn) {
                    nextBtn.classList.toggle('hidden', currentStep === totalSteps - 1);
                }

                if (submitBtn) {
                    submitBtn.classList.toggle('hidden', currentStep !== totalSteps - 1);
                }

                indicators.forEach((indicator, index) => {
                    if (index === currentStep) {
                        setIndicatorState(indicator, 'active');
                    } else if (index < currentStep) {
                        setIndicatorState(indicator, 'done');
                    } else {
                        setIndicatorState(indicator, 'idle');
                    }
                });

                if (shouldScroll) {
                    wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            };

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (currentStep < totalSteps - 1) {
                        currentStep += 1;
                        updateUI(true);
                    }
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep -= 1;
                        updateUI(true);
                    }
                });
            }

            indicators.forEach(indicator => {
                indicator.addEventListener('click', () => {
                    const target = Number.parseInt(indicator.dataset.stepIndicator ?? '0', 10);
                    if (!Number.isNaN(target)) {
                        currentStep = Math.min(Math.max(target, 0), totalSteps - 1);
                        updateUI(true);
                    }
                });
            });

            updateUI(false);
        });
    </script>
@endsection
