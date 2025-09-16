<?php

namespace App\Filament\Resources\EnrollmentKeyResource\Pages;

use App\Filament\Resources\EnrollmentKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEnrollmentKey extends ViewRecord
{
    protected static string $resource = EnrollmentKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
