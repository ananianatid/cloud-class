<?php

namespace App\Filament\Resources\SeanceTemplateResource\Pages;

use App\Filament\Resources\SeanceTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeanceTemplates extends ListRecords
{
    protected static string $resource = SeanceTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
