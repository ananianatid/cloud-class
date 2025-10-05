<?php

namespace App\Filament\Resources\CahierPresenceResource\Pages;

use App\Filament\Resources\CahierPresenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCahierPresence extends ViewRecord
{
    protected static string $resource = CahierPresenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
