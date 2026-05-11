<?php

namespace App\Filament\Resources\CurriculumItems;

use App\Filament\Resources\CurriculumItems\Pages\CreateCurriculumItem;
use App\Filament\Resources\CurriculumItems\Pages\EditCurriculumItem;
use App\Filament\Resources\CurriculumItems\Pages\ListCurriculumItems;
use App\Filament\Resources\CurriculumItems\Pages\ViewCurriculumItem;
use App\Filament\Resources\CurriculumItems\Schemas\CurriculumItemForm;
use App\Filament\Resources\CurriculumItems\Schemas\CurriculumItemInfolist;
use App\Filament\Resources\CurriculumItems\Tables\CurriculumItemsTable;
use App\Models\CurriculumItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CurriculumItemResource extends Resource
{
    protected static ?string $model = CurriculumItem::class;

    protected static ?string $pluralLabel = 'Kurikulum';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CurriculumItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CurriculumItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurriculumItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCurriculumItems::route('/'),
            'create' => CreateCurriculumItem::route('/create'),
            'view' => ViewCurriculumItem::route('/{record}'),
            'edit' => EditCurriculumItem::route('/{record}/edit'),
        ];
    }
}
