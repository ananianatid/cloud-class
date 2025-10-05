<?php

namespace App\Filament\Resources\SeanceTemplateResource\Pages;

use App\Filament\Resources\SeanceTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeanceTemplate extends EditRecord
{
    protected static string $resource = SeanceTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
