<?php

namespace App\Filament\Resources\TexteResource\Pages;

use App\Filament\Resources\TexteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTexte extends EditRecord
{
    protected static string $resource = TexteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
