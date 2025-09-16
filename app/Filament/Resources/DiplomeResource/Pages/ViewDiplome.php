<?php

namespace App\Filament\Resources\DiplomeResource\Pages;

use App\Filament\Resources\DiplomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDiplome extends ViewRecord
{
    protected static string $resource = DiplomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
