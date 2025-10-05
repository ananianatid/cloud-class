<?php

namespace App\Filament\Resources\SeanceOccurrenceResource\Pages;

use App\Filament\Resources\SeanceOccurrenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSeanceOccurrence extends ViewRecord
{
    protected static string $resource = SeanceOccurrenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
