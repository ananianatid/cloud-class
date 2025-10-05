<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeanceOccurrenceResource\Pages;
use App\Filament\Resources\SeanceOccurrenceResource\RelationManagers;
use App\Models\SeanceOccurrence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeanceOccurrenceResource extends Resource
{
    protected static ?string $model = SeanceOccurrence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('template_id')
                    ->relationship('template', 'id')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('heure_debut')
                    ->required(),
                Forms\Components\TextInput::make('heure_fin')
                    ->required(),
                Forms\Components\Select::make('salle_id')
                    ->relationship('salle', 'id')
                    ->required(),
                Forms\Components\Select::make('professeur_id')
                    ->relationship('professeur', 'id')
                    ->required(),
                Forms\Components\TextInput::make('statut')
                    ->required(),
                Forms\Components\Textarea::make('raison_annulation')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes_seance')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('template.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('heure_debut'),
                Tables\Columns\TextColumn::make('heure_fin'),
                Tables\Columns\TextColumn::make('salle.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('professeur.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('statut'),
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
            'index' => Pages\ListSeanceOccurrences::route('/'),
            'create' => Pages\CreateSeanceOccurrence::route('/create'),
            'view' => Pages\ViewSeanceOccurrence::route('/{record}'),
            'edit' => Pages\EditSeanceOccurrence::route('/{record}/edit'),
        ];
    }
}
