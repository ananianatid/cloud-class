<?php

namespace App\Filament\Resources\SeanceOccurrenceResource\Pages;

use App\Filament\Resources\SeanceOccurrenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeanceOccurrences extends ListRecords
{
    protected static string $resource = SeanceOccurrenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
