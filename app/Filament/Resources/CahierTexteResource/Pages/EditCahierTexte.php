<?php

namespace App\Filament\Resources\CahierTexteResource\Pages;

use App\Filament\Resources\CahierTexteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCahierTexte extends EditRecord
{
    protected static string $resource = CahierTexteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
