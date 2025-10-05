<?php

namespace App\Filament\Resources\CleInscriptionResource\Pages;

use App\Filament\Resources\CleInscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCleInscription extends ViewRecord
{
    protected static string $resource = CleInscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
