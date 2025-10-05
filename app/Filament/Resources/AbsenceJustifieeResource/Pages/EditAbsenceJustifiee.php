<?php

namespace App\Filament\Resources\AbsenceJustifieeResource\Pages;

use App\Filament\Resources\AbsenceJustifieeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbsenceJustifiee extends EditRecord
{
    protected static string $resource = AbsenceJustifieeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
