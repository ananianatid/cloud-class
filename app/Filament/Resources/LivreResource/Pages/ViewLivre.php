<?php

namespace App\Filament\Resources\LivreResource\Pages;

use App\Filament\Resources\LivreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLivre extends ViewRecord
{
    protected static string $resource = LivreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
