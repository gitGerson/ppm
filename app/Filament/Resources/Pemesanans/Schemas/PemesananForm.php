<?php

namespace App\Filament\Resources\Pemesanans\Schemas;

use App\Models\Item;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PemesananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pemesanan')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Pemesan')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('order_date')
                            ->label('Tanggal Pemesanan')
                            ->required(),
                        TextInput::make('address')
                            ->label('Alamat')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        TextInput::make('nama_kos')
                            ->label('Nama Kos')
                            ->maxLength(255),
                        TextInput::make('total_amount')
                            ->label('Total Pemesanan')
                            ->required()
                            ->numeric()
                            ->readOnly()
                            ->prefix('Rp')
                            ->default(0),
                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'belum_lunas' => 'Belum Lunas',
                                'lunas' => 'Lunas',
                            ])
                            ->default('belum_lunas')
                            ->required(),
                        Select::make('seragam_ppm_size')
                            ->label('Ukuran Seragam PPM')
                            ->options([
                                'S' => 'S',
                                'M' => 'M',
                                'L' => 'L',
                                'XL' => 'XL',
                                'XXL' => 'XXL',
                            ]),
                        Select::make('baju_asad_size')
                            ->label('Ukuran Baju ASAD')
                            ->options([
                                'S' => 'S',
                                'M' => 'M',
                                'L' => 'L',
                                'XL' => 'XL',
                                'XXL' => 'XXL',
                            ]),
                        FileUpload::make('bukti_pembayaran_path')
                            ->label('Bukti Pembayaran')
                            ->disk('public')
                            ->directory('bukti-pembayaran')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                            ->maxSize(2048)
                            ->downloadable()
                            ->openable()
                            ->preventFilePathTampering()
                            ->columnSpanFull(),
                    ]),
                Section::make('Detail Item')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('detailPemesanans')
                            ->label('Item Pemesanan')
                            ->relationship()
                            ->defaultItems(1)
                            ->live()
                            ->addActionLabel('Tambah Item')
                            ->afterStateUpdated(fn (Get $get, Set $set) => $set('total_amount', self::calculateGrandTotal($get('detailPemesanans'))))
                            ->schema([
                                Select::make('item_id')
                                    ->label('Item')
                                    ->relationship('item', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set, $state) => $set('total_amount', self::calculateLineTotal($state, $get('quantity')))),
                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set, $state) => $set('total_amount', self::calculateLineTotal($get('item_id'), $state))),
                                TextInput::make('total_amount')
                                    ->label('Subtotal')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('Rp')
                                    ->default(0),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $details
     */
    private static function calculateGrandTotal(?array $details): int
    {
        return collect($details ?? [])
            ->sum(fn (array $detail): int => (int) ($detail['total_amount'] ?? 0));
    }

    private static function calculateLineTotal($itemId, $quantity): int
    {
        if (blank($itemId) || blank($quantity)) {
            return 0;
        }

        $item = Item::query()->find($itemId);

        return ($item?->price ?? 0) * (int) $quantity;
    }
}
