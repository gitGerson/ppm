<?php

namespace App\Filament\Resources\CurriculumItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CurriculumItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item Kurikulum')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Select::make('icon')
                            ->label('Ikon')
                            ->options([
                                'book' => 'Book',
                                'layers' => 'Layers',
                                'spark' => 'Spark',
                                'shield' => 'Shield',
                                'star' => 'Star',
                            ])
                            ->required(),
                        Select::make('theme')
                            ->label('Tema')
                            ->options([
                                'sage' => 'Sage',
                                'sand' => 'Sand',
                                'mint' => 'Mint',
                                'olive' => 'Olive',
                                'sky' => 'Sky',
                            ])
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
