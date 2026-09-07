<?php

namespace App\Filament\Resources\DetailSantris\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab as TabForm;
use Filament\Schemas\Schema;

class DetailSantriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        TabForm::make('Data Diri Santri')
                            ->columns(2)
                            ->columnSpanFull()
                            ->schema([
                                Select::make('user_id')
                                    ->label('ID Santri')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->required(),
                                TextInput::make('nama_lengkap')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255),
                                Radio::make('status_anak')
                                    ->label('Status Anak')
                                    ->inline()
                                    ->options([
                                        'Lengkap' => 'Lengkap',
                                        'Yatim' => 'Yatim',
                                        'Piatu' => 'Piatu',
                                        'Yatim Piatu' => 'Yatim Piatu',
                                    ]),
                                TextInput::make('nama_panggilan')
                                    ->label('Nama Panggilan')
                                    ->maxLength(255),
                                TextInput::make('anak_ke')
                                    ->label('Anak Ke')
                                    ->numeric()
                                    ->minValue(0),
                                TextInput::make('NISN')
                                    ->label('NISN')
                                    ->maxLength(20),
                                TextInput::make('jumlah_saudara')
                                    ->label('Jumlah Saudara')
                                    ->numeric()
                                    ->minValue(0),
                                TextInput::make('NIK')
                                    ->label('NIK')
                                    ->maxLength(16),
                                TextInput::make('hobi')
                                    ->label('Hobi')
                                    ->maxLength(65535),
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->maxLength(255),
                                TextInput::make('cita-cita')
                                    ->label('Cita-cita')
                                    ->maxLength(255),
                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir'),
                                Radio::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->inline()
                                    ->options([
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan' => 'Perempuan',
                                    ]),
                            ]),
                        TabForm::make('Data Fisik & Pendidikan')
                            ->columns(2)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('tinggi_badan')
                                    ->label('Tinggi Badan (cm)')
                                    ->numeric()
                                    ->minValue(0),
                                Radio::make('status_bpjs')
                                    ->label('Status BPJS')
                                    ->inline()
                                    ->options([
                                        'Tidak Memiliki' => 'Tidak Memiliki',
                                        'Memiliki' => 'Memiliki',
                                        'Memiliki KIS' => 'Memiliki KIS',
                                    ]),
                                TextInput::make('berat_badan')
                                    ->label('Berat Badan (kg)')
                                    ->numeric()
                                    ->minValue(0),
                                Radio::make('status_pip')
                                    ->label('Status PIP')
                                    ->inline()
                                    ->options([
                                        'Tidak Memiliki' => 'Tidak Memiliki',
                                        'Memiliki' => 'Memiliki',
                                    ]),
                                TextInput::make('golongan_darah')
                                    ->label('Golongan Darah')
                                    ->maxLength(3),
                                TextInput::make('riwayat_penyakit')
                                    ->label('Riwayat Penyakit'),

                            ]),
                        TabForm::make('Data Pendidikan')
                            ->columns(2)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('ijazah_terakhir')
                                    ->label('Ijazah Terakhir')
                                    ->maxLength(255),
                                Radio::make('is_mondok')
                                    ->label('Sudah Pernah Mondok')
                                    ->inline()
                                    ->boolean(trueLabel: 'Sudah Pernah', falseLabel: 'Belum Pernah'),
                                TextInput::make('nama_sekolah_asal')
                                    ->label('Nama Sekolah/Universitas')
                                    ->maxLength(255),
                                Radio::make('khatam')
                                    ->label('Khatam Hadist Besar')
                                    ->columns(3)
                                    ->options([
                                        'Bukhori' => 'Bukhori',
                                        'Nasai' => 'Nasai',
                                        'Muslim' => 'Muslim',
                                        'Tirmidzi' => 'Tirmidzi',
                                        'Abu Daud' => 'Abu Daud',
                                        'Ibnu Majah' => 'Ibnu Majah',
                                    ]),
                                TextInput::make('prodi_sekolah_asal')
                                    ->label('Program Studi')
                                    ->maxLength(255),
                                TextInput::make('tahun_masuk_sekolah')
                                    ->label('Tahun Masuk Sekolah')
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(now()->year + 1),
                                TextInput::make('tahun_masuk_ppm')
                                    ->label('Tahun Masuk PPM')
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(now()->year + 1),
                            ]),
                        TabForm::make('Kontak & Alamat')
                            ->columns(2)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('asalkelompoksambung')
                                    ->label('Asal Kelompok Sambung')
                                    ->maxLength(255),
                                TextInput::make('kabupaten')
                                    ->label('Kabupaten/Kota')
                                    ->maxLength(255),
                                TextInput::make('desa')
                                    ->label('Desa')
                                    ->maxLength(255),
                                TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->maxLength(255),
                                Textarea::make('alamat')
                                    ->label('Alamat')
                                    ->columnSpan(2),
                                TextInput::make('kodepos')
                                    ->label('Kode Pos')
                                    ->maxLength(10),
                                TextInput::make('rtrw')
                                    ->label('RT / RW')
                                    ->maxLength(20),
                                TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->maxLength(255),
                                TextInput::make('no_hp')
                                    ->label('No. HP')
                                    ->tel()
                                    ->maxLength(20),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('media_sosial')
                                    ->label('Media Sosial')
                                    ->maxLength(255),
                            ]),
                        TabForm::make('Aset & Dokumen')
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                Radio::make('is_motor')
                                    ->label('Memiliki Motor')
                                    ->inline()
                                    ->boolean(trueLabel: 'Memiliki', falseLabel: 'Tidak Memiliki'),
                                Radio::make('is_sepeda')
                                    ->label('Memiliki Sepeda')
                                    ->inline()
                                    ->boolean(trueLabel: 'Memiliki', falseLabel: 'Tidak Memiliki'),
                                Radio::make('is_laptop')
                                    ->label('Memiliki Laptop')
                                    ->inline()
                                    ->boolean(trueLabel: 'Memiliki', falseLabel: 'Tidak Memiliki'),
                                Radio::make('sim')
                                    ->label('SIM')
                                    ->inline()
                                    ->options([
                                        'Memiliki' => 'Memiliki',
                                        'Tidak Memiliki' => 'Tidak Memiliki',
                                    ]),
                                FileUpload::make('image_ktp_path')
                                    ->label('Scan KTP')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('detail-santri/ktp')
                                    ->image()
                                    ->imagePreviewHeight('150')
                                    ->nullable()
                                    ->columnSpan(2),
                                FileUpload::make('image_pasfoto_path')
                                    ->label('Pas Foto')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('detail-santri/pasfoto')
                                    ->image()
                                    ->imagePreviewHeight('150')
                                    ->nullable()
                                    ->columnSpan(2),
                            ]),
                        TabForm::make('Data Keluarga')
                            ->columns(1)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('no_kk')
                                    ->label('Nomor KK')
                                    ->maxLength(16)
                                    ->columnSpanFull(),
                                Section::make('Data Ayah')
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextInput::make('nama_ayah')
                                            ->label('Nama Ayah')
                                            ->maxLength(255),
                                        TextInput::make('pendidikan_ayah')
                                            ->label('Pendidikan Ayah')
                                            ->maxLength(255),
                                        TextInput::make('nik_ayah')
                                            ->label('NIK Ayah')
                                            ->maxLength(16),
                                        TextInput::make('pekerjaan_ayah')
                                            ->label('Pekerjaan Ayah')
                                            ->maxLength(255),
                                        TextInput::make('tempat_lahir_ayah')
                                            ->label('Tempat Lahir Ayah')
                                            ->maxLength(255),
                                        DatePicker::make('tanggal_lahir_ayah')
                                            ->label('Tanggal Lahir Ayah'),
                                        TextInput::make('penghasilan_ayah')
                                            ->label('Penghasilan Ayah')
                                            ->numeric()
                                            ->minValue(0),
                                        TextInput::make('no_hp_ayah')
                                            ->label('No. HP Ayah')
                                            ->tel()
                                            ->maxLength(20),
                                        Radio::make('is_ayah_alive')
                                            ->label('Status Ayah')
                                            ->inline()
                                            ->columns(2)
                                            ->boolean(trueLabel: 'Hidup', falseLabel: 'Meninggal'),
                                    ]),

                                Section::make('Data Ibu')
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextInput::make('nama_ibu')
                                            ->label('Nama Ibu')
                                            ->maxLength(255),
                                        TextInput::make('pendidikan_ibu')
                                            ->label('Pendidikan Ibu')
                                            ->maxLength(255),
                                        TextInput::make('nik_ibu')
                                            ->label('NIK Ibu')
                                            ->maxLength(16),
                                        TextInput::make('pekerjaan_ibu')
                                            ->label('Pekerjaan Ibu')
                                            ->maxLength(255),
                                        TextInput::make('tempat_lahir_ibu')
                                            ->label('Tempat Lahir Ibu')
                                            ->maxLength(255),
                                        DatePicker::make('tanggal_lahir_ibu')
                                            ->label('Tanggal Lahir Ibu'),
                                        TextInput::make('penghasilan_ibu')
                                            ->label('Penghasilan Ibu')
                                            ->numeric()
                                            ->minValue(0),
                                        TextInput::make('no_hp_ibu')
                                            ->label('No. HP Ibu')
                                            ->tel()
                                            ->maxLength(20),
                                        Radio::make('is_ibu_alive')
                                            ->label('Status Ibu')
                                            ->inline()
                                            ->columnSpan(2)
                                            ->boolean(trueLabel: 'Hidup', falseLabel: 'Meninggal'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
