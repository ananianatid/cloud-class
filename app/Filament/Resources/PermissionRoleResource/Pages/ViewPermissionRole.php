<?php

namespace App\Filament\Resources\PermissionRoleResource\Pages;

use App\Filament\Resources\PermissionRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPermissionRole extends ViewRecord
{
    protected static string $resource = PermissionRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
