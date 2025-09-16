<?php

namespace App\Filament\Resources\UniteEnseignementResource\Pages;

use App\Filament\Resources\UniteEnseignementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniteEnseignement extends EditRecord
{
    protected static string $resource = UniteEnseignementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
