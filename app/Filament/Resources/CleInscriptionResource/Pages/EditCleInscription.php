<?php

namespace App\Filament\Resources\CleInscriptionResource\Pages;

use App\Filament\Resources\CleInscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCleInscription extends EditRecord
{
    protected static string $resource = CleInscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
