<?php

namespace App\Filament\Resources\Sliders\Schemas;

use App\Models\Slider;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SliderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero Slider')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul'),
                        TextEntry::make('sort_order')
                            ->label('Urutan'),
                        TextEntry::make('alt_text')
                            ->label('Alt Text'),
                        TextEntry::make('is_active')
                            ->label('Aktif')
                            ->badge(),
                        ImageEntry::make('image_path')
                            ->label('Gambar')
                            ->state(fn (Slider $record): string => $record->imageUrl())
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
