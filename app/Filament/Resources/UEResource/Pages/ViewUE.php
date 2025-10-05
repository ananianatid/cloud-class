<?php

namespace App\Filament\Resources\UEResource\Pages;

use App\Filament\Resources\UEResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUE extends ViewRecord
{
    protected static string $resource = UEResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
