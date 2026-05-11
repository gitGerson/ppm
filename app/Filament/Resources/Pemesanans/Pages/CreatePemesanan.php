<?php

namespace App\Filament\Resources\Pemesanans\Pages;

use App\Filament\Resources\Pemesanans\PemesananResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePemesanan extends CreateRecord
{
    protected static string $resource = PemesananResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_amount'] = collect($data['detailPemesanans'] ?? [])
            ->sum(fn (array $detail): int => (int) ($detail['total_amount'] ?? 0));
        $data['payment_status'] = $data['payment_status'] ?? 'belum_lunas';

        return $data;
    }

    public function getTitle(): string
    {
        return 'Tambah Data Pemesanan';
    }
}
