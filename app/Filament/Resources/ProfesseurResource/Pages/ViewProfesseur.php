<?php

namespace App\Filament\Resources\ProfesseurResource\Pages;

use App\Filament\Resources\ProfesseurResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProfesseur extends ViewRecord
{
    protected static string $resource = ProfesseurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
