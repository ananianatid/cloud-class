<?php

namespace App\Filament\Resources\CahierTexteResource\Pages;

use App\Filament\Resources\CahierTexteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCahierTextes extends ListRecords
{
    protected static string $resource = CahierTexteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
