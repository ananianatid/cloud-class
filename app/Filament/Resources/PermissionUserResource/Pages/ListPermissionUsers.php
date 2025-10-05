<?php

namespace App\Filament\Resources\PermissionUserResource\Pages;

use App\Filament\Resources\PermissionUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermissionUsers extends ListRecords
{
    protected static string $resource = PermissionUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
