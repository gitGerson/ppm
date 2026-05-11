<?php

namespace App\Filament\Resources\CurriculumItems\Pages;

use App\Filament\Resources\CurriculumItems\CurriculumItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCurriculumItems extends ListRecords
{
    protected static string $resource = CurriculumItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
