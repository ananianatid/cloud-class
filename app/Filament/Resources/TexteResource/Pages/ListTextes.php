<?php

namespace App\Filament\Resources\TexteResource\Pages;

use App\Filament\Resources\TexteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTextes extends ListRecords
{
    protected static string $resource = TexteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
