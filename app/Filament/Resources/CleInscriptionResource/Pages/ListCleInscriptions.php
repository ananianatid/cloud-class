<?php

namespace App\Filament\Resources\CleInscriptionResource\Pages;

use App\Filament\Resources\CleInscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCleInscriptions extends ListRecords
{
    protected static string $resource = CleInscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
