<?php

namespace App\Filament\Resources\Pemesanans\Pages;

use App\Filament\Resources\Pemesanans\PemesananResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPemesanan extends EditRecord
{
    protected static string $resource = PemesananResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total_amount'] = collect($data['detailPemesanans'] ?? [])
            ->sum(fn (array $detail): int => (int) ($detail['total_amount'] ?? 0));

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Lihat Data Pemesanan'),
            DeleteAction::make()->label('Hapus Data Pemesanan'),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Data Pemesanan';
    }
}
