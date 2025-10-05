<?php

namespace App\Filament\Resources\DiplomeResource\Pages;

use App\Filament\Resources\DiplomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiplomes extends ListRecords
{
    protected static string $resource = DiplomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
