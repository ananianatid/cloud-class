<?php

namespace App\Filament\Resources\EnrollmentKeyResource\Pages;

use App\Filament\Resources\EnrollmentKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnrollmentKey extends EditRecord
{
    protected static string $resource = EnrollmentKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
