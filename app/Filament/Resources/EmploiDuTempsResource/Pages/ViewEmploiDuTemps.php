<?php

namespace App\Filament\Resources\EmploiDuTempsResource\Pages;

use App\Filament\Resources\EmploiDuTempsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmploiDuTemps extends ViewRecord
{
    protected static string $resource = EmploiDuTempsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
