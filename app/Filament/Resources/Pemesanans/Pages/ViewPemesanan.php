<?php

namespace App\Filament\Resources\Pemesanans\Pages;

use App\Filament\Resources\Pemesanans\PemesananResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPemesanan extends ViewRecord
{
    protected static string $resource = PemesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Edit Pemesanan'),
        ];
    }

    public function getTitle(): string
    {
        return 'Lihat Data Pemesanan';
    }
}
