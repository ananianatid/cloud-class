<?php

namespace App\Filament\Resources\UniteEnseignementResource\Pages;

use App\Filament\Resources\UniteEnseignementResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUniteEnseignement extends ViewRecord
{
    protected static string $resource = UniteEnseignementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
