<?php

namespace App\Filament\Resources\PermissionUserResource\Pages;

use App\Filament\Resources\PermissionUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermissionUser extends EditRecord
{
    protected static string $resource = PermissionUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
