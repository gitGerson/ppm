<?php

namespace App\Filament\Resources\Beritas\Tables;

use App\Models\Berita;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BeritasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                ToggleColumn::make('visible')
                    ->label('Visibilitas')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(Berita::categoryOptions()),
            ])
            ->recordActions([
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
