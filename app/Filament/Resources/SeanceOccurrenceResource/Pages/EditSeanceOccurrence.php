<?php

namespace App\Filament\Resources\SeanceOccurrenceResource\Pages;

use App\Filament\Resources\SeanceOccurrenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeanceOccurrence extends EditRecord
{
    protected static string $resource = SeanceOccurrenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
