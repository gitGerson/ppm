<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('asset_video')
                            ->label('Asset Video')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                            ->maxSize(20480)
                            ->disk('public')
                            ->directory('events/videos')
                            ->visibility('public')
                            ->downloadable()
                            ->openable()
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
