<?php

namespace App\Filament\Resources\EvenementResource\Pages;

use App\Filament\Resources\EvenementResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEvenement extends ViewRecord
{
    protected static string $resource = EvenementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
