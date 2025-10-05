<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionUserResource\Pages;
use App\Filament\Resources\PermissionUserResource\RelationManagers;
use App\Models\PermissionUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionUserResource extends Resource
{
    protected static ?string $model = PermissionUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'utilisateurs et permissions';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissionUsers::route('/'),
            'create' => Pages\CreatePermissionUser::route('/create'),
            'view' => Pages\ViewPermissionUser::route('/{record}'),
            'edit' => Pages\EditPermissionUser::route('/{record}/edit'),
        ];
    }
}
