<?php

namespace App\Filament\Resources\SemestreResource\RelationManagers;

use App\Services\CacheService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatieresRelationManager extends RelationManager
{
    protected static string $relationship = 'matieres';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unite_id')
                    ->label('Unité d\'enseignement')
                    ->options(CacheService::getUnitesEnseignement()->pluck('nom', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Sélectionnez l\'unité d\'enseignement pour cette matière'),
                Forms\Components\Select::make('enseignant_id')
                    ->label('Enseignant responsable')
                    ->options(function () {
                        return \App\Models\Enseignant::with('user')->get()->mapWithKeys(function ($enseignant) {
                            return [$enseignant->id => $enseignant->user->name ?? 'Enseignant #' . $enseignant->id];
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Sélectionnez l\'enseignant qui sera responsable de cette matière'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['unite', 'enseignant.user'])
                    ->join('unite_enseignements', 'matieres.unite_id', '=', 'unite_enseignements.id')
                    ->orderBy('unite_enseignements.nom')
                    ->select('matieres.*');
            })
            ->recordTitleAttribute('unite.nom')
            ->columns([
                Tables\Columns\TextColumn::make('unite.nom')
                    ->label('Unité d\'enseignement')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->wrap(),
                Tables\Columns\TextColumn::make('unite.code')
                    ->label('Code UE')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->width('100px'),
                Tables\Columns\TextColumn::make('enseignant.user.name')
                    ->label('Enseignant')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        return $record->enseignant->user->name ?? 'Enseignant #' . $record->enseignant_id;
                    }),
                Tables\Columns\TextColumn::make('unite.credits')
                    ->label('Crédits')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->suffix(' ECTS'),
                Tables\Columns\TextColumn::make('unite.volume_horaire')
                    ->label('Volume')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->suffix('h'),
                Tables\Columns\TextColumn::make('unite.description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unite_id')
                    ->label('Unité d\'enseignement')
                    ->relationship('unite', 'nom'),
                Tables\Filters\SelectFilter::make('enseignant_id')
                    ->label('Enseignant')
                    ->options(function () {
                        return \App\Models\Enseignant::with('user')->get()->mapWithKeys(function ($enseignant) {
                            return [$enseignant->id => $enseignant->user->name ?? 'Enseignant #' . $enseignant->id];
                        });
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter une matière')
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('update_enseignant')
                        ->label('Modifier l\'enseignant')
                        ->icon('heroicon-o-academic-cap')
                        ->form([
                            Forms\Components\Select::make('enseignant_id')
                                ->label('Nouvel enseignant')
                                ->options(function () {
                                    return \App\Models\Enseignant::with('user')->get()->mapWithKeys(function ($enseignant) {
                                        return [$enseignant->id => $enseignant->user->name ?? 'Enseignant #' . $enseignant->id];
                                    });
                                })
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['enseignant_id' => $data['enseignant_id']]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('unite_enseignements.nom')
            ->emptyStateHeading('Aucune matière')
            ->emptyStateDescription('Commencez par ajouter des matières à ce semestre.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
