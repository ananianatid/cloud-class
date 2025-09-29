<?php

namespace App\Filament\Resources\EmploiDuTempsResource\RelationManagers;

use App\Services\CacheService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoursRelationManager extends RelationManager
{
    protected static string $relationship = 'cours';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('matiere_id')
                    ->label('Matière')
                    ->options(function () {
                        return CacheService::getMatieres()->pluck('unite.nom', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('salle_id')
                    ->label('Salle')
                    ->options(CacheService::getSalles()->pluck('numero', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('jour')
                    ->label('Jour')
                    ->options([
                        'lundi' => 'Lundi',
                        'mardi' => 'Mardi',
                        'mercredi' => 'Mercredi',
                        'jeudi' => 'Jeudi',
                        'vendredi' => 'Vendredi',
                        'samedi' => 'Samedi',
                        'dimanche' => 'Dimanche',
                    ])
                    ->required(),
                Forms\Components\TimePicker::make('debut')
                    ->label('Heure de début')
                    ->required(),
                Forms\Components\TimePicker::make('fin')
                    ->label('Heure de fin')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'cours' => 'Cours',
                        'td&tp' => 'TD & TP',
                        'evaluation' => 'Évaluation',
                        'devoir' => 'Devoir',
                        'examen' => 'Examen',
                        'autre' => 'Autre',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['matiere.unite', 'salle'])
                    ->orderBy('jour')
                    ->orderBy('debut');
            })
            ->recordTitleAttribute('matiere.unite.nom')
            ->columns([
                Tables\Columns\TextColumn::make('jour')
                    ->label('Jour')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lundi' => 'gray',
                        'mardi' => 'gray',
                        'mercredi' => 'gray',
                        'jeudi' => 'gray',
                        'vendredi' => 'gray',
                        'samedi' => 'warning',
                        'dimanche' => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('debut')
                    ->label('Début')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fin')
                    ->label('Fin')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('matiere.unite.nom')
                    ->label('Matière')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salle.numero')
                    ->label('Salle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cours' => 'success',
                        'td&tp' => 'info',
                        'evaluation' => 'warning',
                        'devoir' => 'warning',
                        'examen' => 'danger',
                        'autre' => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jour')
                    ->options([
                        'lundi' => 'Lundi',
                        'mardi' => 'Mardi',
                        'mercredi' => 'Mercredi',
                        'jeudi' => 'Jeudi',
                        'vendredi' => 'Vendredi',
                        'samedi' => 'Samedi',
                        'dimanche' => 'Dimanche',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'cours' => 'Cours',
                        'td&tp' => 'TD & TP',
                        'evaluation' => 'Évaluation',
                        'devoir' => 'Devoir',
                        'examen' => 'Examen',
                        'autre' => 'Autre',
                    ]),
                Tables\Filters\SelectFilter::make('salle_id')
                    ->label('Salle')
                    ->relationship('salle', 'numero'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('jour')
            ->paginated([10, 25, 50, 100]);
    }
}
