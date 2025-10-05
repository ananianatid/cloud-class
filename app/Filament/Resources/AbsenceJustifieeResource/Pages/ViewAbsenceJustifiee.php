<?php

namespace App\Filament\Resources\AbsenceJustifieeResource\Pages;

use App\Filament\Resources\AbsenceJustifieeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAbsenceJustifiee extends ViewRecord
{
    protected static string $resource = AbsenceJustifieeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
