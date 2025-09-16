<?php

namespace App\Filament\Resources\DiplomeResource\Pages;

use App\Filament\Resources\DiplomeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiplome extends EditRecord
{
    protected static string $resource = DiplomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
