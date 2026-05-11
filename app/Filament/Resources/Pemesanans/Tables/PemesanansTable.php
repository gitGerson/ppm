<?php

namespace App\Filament\Resources\Pemesanans\Tables;

use App\Models\Pemesanan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PemesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Pemesan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(40)
                    ->tooltip(fn ($record): string => $record->address),
                TextColumn::make('detail_pemesanans_count')
                    ->label('Jumlah Item')
                    ->counts('detailPemesanans')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state === 'lunas' ? 'Lunas' : 'Belum Lunas')
                    ->color(fn (?string $state): string => $state === 'lunas' ? 'success' : 'warning')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('markAsPaid')
                    ->label('Konfirmasi Lunas')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Pemesanan $record): bool => $record->payment_status !== 'lunas')
                    ->action(function (Pemesanan $record): void {
                        $record->update([
                            'payment_status' => 'lunas',
                        ]);
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
