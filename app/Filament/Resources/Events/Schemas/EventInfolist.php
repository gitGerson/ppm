<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('judul')
                            ->label('Judul'),
                        TextEntry::make('asset_video')
                            ->label('Asset Video')
                            ->url(fn (?string $state): ?string => $state ? asset('storage/'.$state) : null)
                            ->openUrlInNewTab(),
                    ]),
            ]);
    }
}
