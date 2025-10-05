<?php

namespace App\Filament\Resources\SemestreResource\Pages;

use App\Filament\Resources\SemestreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSemestre extends ViewRecord
{
    protected static string $resource = SemestreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
