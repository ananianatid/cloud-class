<?php

namespace App\Filament\Resources\CategorieLivreResource\Pages;

use App\Filament\Resources\CategorieLivreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategorieLivre extends EditRecord
{
    protected static string $resource = CategorieLivreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
