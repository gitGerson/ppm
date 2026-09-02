<?php

namespace App\Filament\Resources\Pengumumen\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PengumumanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required(),
                DatePicker::make('date')
                    ->label('Date')
                    ->required(),
                FileUpload::make('image_url')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('pengumuman')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->nullable(),
                RichEditor::make('content')
                    ->label('Content')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
