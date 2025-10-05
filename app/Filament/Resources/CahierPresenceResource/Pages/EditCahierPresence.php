<?php

namespace App\Filament\Resources\CahierPresenceResource\Pages;

use App\Filament\Resources\CahierPresenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCahierPresence extends EditRecord
{
    protected static string $resource = CahierPresenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
