<?php

namespace App\Filament\Resources\RoleUserResource\Pages;

use App\Filament\Resources\RoleUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRoleUser extends ViewRecord
{
    protected static string $resource = RoleUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
