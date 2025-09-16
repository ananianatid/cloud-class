<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoursResource\Pages;
use App\Filament\Resources\CoursResource\RelationManagers;
use App\Models\Cours;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoursResource extends Resource
{
    protected static ?string $model = Cours::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('emploi_du_temps_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('matiere_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('salle_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jour')
                    ->required(),
                Forms\Components\TextInput::make('debut')
                    ->required(),
                Forms\Components\TextInput::make('fin')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emploi_du_temps_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matiere_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salle_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jour'),
                Tables\Columns\TextColumn::make('debut'),
                Tables\Columns\TextColumn::make('fin'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCours::route('/'),
            'create' => Pages\CreateCours::route('/create'),
            'view' => Pages\ViewCours::route('/{record}'),
            'edit' => Pages\EditCours::route('/{record}/edit'),
        ];
    }
}
