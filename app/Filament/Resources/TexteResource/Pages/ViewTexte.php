<?php

namespace App\Filament\Resources\TexteResource\Pages;

use App\Filament\Resources\TexteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTexte extends ViewRecord
{
    protected static string $resource = TexteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
