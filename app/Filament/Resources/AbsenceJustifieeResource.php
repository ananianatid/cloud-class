<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsenceJustifieeResource\Pages;
use App\Filament\Resources\AbsenceJustifieeResource\RelationManagers;
use App\Models\AbsenceJustifiee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsenceJustifieeResource extends Resource
{
    protected static ?string $model = AbsenceJustifiee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Ressources pédagogiques';
    protected static ?int $navigationSort = 5;
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
            'index' => Pages\ListAbsenceJustifiees::route('/'),
            'create' => Pages\CreateAbsenceJustifiee::route('/create'),
            'view' => Pages\ViewAbsenceJustifiee::route('/{record}'),
            'edit' => Pages\EditAbsenceJustifiee::route('/{record}/edit'),
        ];
    }
}
