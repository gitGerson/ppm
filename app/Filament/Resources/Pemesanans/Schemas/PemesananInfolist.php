<?php

namespace App\Filament\Resources\Pemesanans\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PemesananInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pemesanan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama Pemesan'),
                        TextEntry::make('order_date')
                            ->label('Tanggal Pemesanan')
                            ->date(),
                        TextEntry::make('address')
                            ->label('Alamat')
                            ->columnSpanFull(),
                        TextEntry::make('total_amount')
                            ->label('Total Pemesanan')
                            ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),
                        TextEntry::make('payment_status')
                            ->label('Status Pembayaran')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => $state === 'lunas' ? 'Lunas' : 'Belum Lunas')
                            ->color(fn (?string $state): string => $state === 'lunas' ? 'success' : 'warning'),
                    ]),
                Section::make('Detail Item')
                    ->schema([
                        RepeatableEntry::make('detailPemesanans')
                            ->label('')
                            ->contained(false)
                            ->table([
                                TableColumn::make('Item'),
                                TableColumn::make('Jumlah'),
                                TableColumn::make('Subtotal'),
                            ])
                            ->schema([
                                TextEntry::make('item.name')
                                    ->label('Item'),
                                TextEntry::make('quantity')
                                    ->label('Jumlah'),
                                TextEntry::make('total_amount')
                                    ->label('Subtotal')
                                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),
                            ]),
                    ]),
            ]);
    }
}
