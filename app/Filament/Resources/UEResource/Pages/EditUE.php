<?php

namespace App\Filament\Resources\UEResource\Pages;

use App\Filament\Resources\UEResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUE extends EditRecord
{
    protected static string $resource = UEResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
