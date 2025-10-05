<?php

namespace App\Filament\Resources\FiliereResource\Pages;

use App\Filament\Resources\FiliereResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFiliere extends ViewRecord
{
    protected static string $resource = FiliereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
