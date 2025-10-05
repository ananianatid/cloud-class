<?php

namespace App\Filament\Resources\SalleResource\Pages;

use App\Filament\Resources\SalleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSalle extends ViewRecord
{
    protected static string $resource = SalleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
