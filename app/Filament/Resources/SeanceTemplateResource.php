<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeanceTemplateResource\Pages;
use App\Filament\Resources\SeanceTemplateResource\RelationManagers;
use App\Models\SeanceTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeanceTemplateResource extends Resource
{
    protected static ?string $model = SeanceTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Organisation';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('emploi_id')
                    ->relationship('emploi', 'id')
                    ->required(),
                Forms\Components\Select::make('matiere_id')
                    ->relationship('matiere', 'id')
                    ->required(),
                Forms\Components\Select::make('salle_id')
                    ->relationship('salle', 'id')
                    ->required(),
                Forms\Components\Select::make('professeur_id')
                    ->relationship('professeur', 'id')
                    ->required(),
                Forms\Components\TextInput::make('jour')
                    ->required(),
                Forms\Components\TextInput::make('heure_debut')
                    ->required(),
                Forms\Components\TextInput::make('heure_fin')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('modalite')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emploi.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matiere.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salle.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('professeur.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jour'),
                Tables\Columns\TextColumn::make('heure_debut'),
                Tables\Columns\TextColumn::make('heure_fin'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('modalite'),
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
            'index' => Pages\ListSeanceTemplates::route('/'),
            'create' => Pages\CreateSeanceTemplate::route('/create'),
            'view' => Pages\ViewSeanceTemplate::route('/{record}'),
            'edit' => Pages\EditSeanceTemplate::route('/{record}/edit'),
        ];
    }
}
