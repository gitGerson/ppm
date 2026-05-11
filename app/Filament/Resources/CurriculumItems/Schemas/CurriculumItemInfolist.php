<?php

namespace App\Filament\Resources\CurriculumItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CurriculumItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item Kurikulum')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul'),
                        TextEntry::make('sort_order')
                            ->label('Urutan'),
                        TextEntry::make('icon')
                            ->label('Ikon')
                            ->badge(),
                        TextEntry::make('theme')
                            ->label('Tema')
                            ->badge(),
                        TextEntry::make('is_active')
                            ->label('Aktif')
                            ->badge()
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
