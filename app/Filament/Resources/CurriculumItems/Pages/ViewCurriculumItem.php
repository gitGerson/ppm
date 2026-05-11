<?php

namespace App\Filament\Resources\CurriculumItems\Pages;

use App\Filament\Resources\CurriculumItems\CurriculumItemResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCurriculumItem extends ViewRecord
{
    protected static string $resource = CurriculumItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
