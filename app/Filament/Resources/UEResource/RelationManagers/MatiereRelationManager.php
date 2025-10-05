<?php

namespace App\Filament\Resources\UEResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatiereRelationManager extends RelationManager
{
    protected static string $relationship = 'matieres';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('intitule')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('semestre_id')
                    ->relationship('semestre', 'numero')
                    ->required(),
                Forms\Components\Select::make('professeur_id')
                    ->relationship('professeur.user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('volume_horaire_cm')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\Toggle::make('is_optionnelle')
                    ->label('Matière optionnelle'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('intitule')
            ->columns([
                Tables\Columns\TextColumn::make('intitule')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semestre.numero')
                    ->label('Semestre')
                    ->sortable(),
                Tables\Columns\TextColumn::make('professeur.user.name')
                    ->label('Professeur')
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume_horaire_cm')
                    ->label('Volume horaire')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_optionnelle')
                    ->label('Optionnelle')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
