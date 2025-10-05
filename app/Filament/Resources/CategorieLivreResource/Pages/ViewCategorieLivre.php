<?php

namespace App\Filament\Resources\CategorieLivreResource\Pages;

use App\Filament\Resources\CategorieLivreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCategorieLivre extends ViewRecord
{
    protected static string $resource = CategorieLivreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
