<?php

namespace App\Filament\Resources\FichierResource\Pages;

use App\Filament\Resources\FichierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFichiers extends ListRecords
{
    protected static string $resource = FichierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
