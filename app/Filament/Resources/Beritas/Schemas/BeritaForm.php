<?php

namespace App\Filament\Resources\Beritas\Schemas;

use App\Models\Berita;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BeritaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Berita')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required(),
                        Select::make('category')
                            ->label('Category')
                            ->options(Berita::categoryOptions())
                            ->default(Berita::CategoryPengajian)
                            ->required(),
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->required()
                            ->default(fn () => auth()->id()),
                        DatePicker::make('date')
                            ->label('Date')
                            ->required(),
                        FileUpload::make('image_url')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('berita')
                            ->visibility('public')
                            ->columnSpanFull()
                            ->nullable(),
                        RichEditor::make('content')
                            ->label('Content')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
