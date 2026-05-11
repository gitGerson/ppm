<?php

namespace App\Filament\Resources\CurriculumItems\Pages;

use App\Filament\Resources\CurriculumItems\CurriculumItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCurriculumItem extends EditRecord
{
    protected static string $resource = CurriculumItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
