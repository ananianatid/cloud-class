<?php

namespace App\Filament\Resources\CahierPresenceResource\Pages;

use App\Filament\Resources\CahierPresenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCahierPresences extends ListRecords
{
    protected static string $resource = CahierPresenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
