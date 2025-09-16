<?php

namespace App\Filament\Resources\UniteEnseignementResource\Pages;

use App\Filament\Resources\UniteEnseignementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniteEnseignements extends ListRecords
{
    protected static string $resource = UniteEnseignementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
