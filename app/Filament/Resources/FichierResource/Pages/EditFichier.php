<?php

namespace App\Filament\Resources\FichierResource\Pages;

use App\Filament\Resources\FichierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFichier extends EditRecord
{
    protected static string $resource = FichierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
