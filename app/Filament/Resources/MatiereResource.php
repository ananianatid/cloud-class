<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatiereResource\Pages;
use App\Filament\Resources\MatiereResource\RelationManagers;
use App\Models\Matiere;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatiereResource extends Resource
{
    protected static ?string $model = Matiere::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestion académique';
    protected static ?int $navigationSort = 6;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('intitule')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('ue_id')
                    ->relationship('ue', 'id')
                    ->required(),
                Forms\Components\Select::make('semestre_id')
                    ->relationship('semestre', 'id')
                    ->required(),
                Forms\Components\Select::make('professeur_id')
                    ->relationship('professeur', 'id')
                    ->required(),
                Forms\Components\TextInput::make('volume_horaire_cm')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_optionnelle')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('intitule')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ue.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semestre.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('professeur.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume_horaire_cm')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_optionnelle')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListMatieres::route('/'),
            'create' => Pages\CreateMatiere::route('/create'),
            'view' => Pages\ViewMatiere::route('/{record}'),
            'edit' => Pages\EditMatiere::route('/{record}/edit'),
        ];
    }
}
