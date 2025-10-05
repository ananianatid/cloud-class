<?php

namespace App\Filament\Resources\ResponsableResource\Pages;

use App\Filament\Resources\ResponsableResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewResponsable extends ViewRecord
{
    protected static string $resource = ResponsableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
