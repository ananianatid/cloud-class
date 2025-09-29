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
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;

class QuickEditCoursRelationManager extends RelationManager
{
    protected static string $relationship = 'cours';

    protected static ?string $title = 'Édition Rapide des Cours';

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
                        'lundi' => 'blue',
                        'mardi' => 'green',
                        'mercredi' => 'yellow',
                        'jeudi' => 'purple',
                        'vendredi' => 'red',
                        'samedi' => 'orange',
                        'dimanche' => 'gray',
                    })
                    ->sortable()
                    ->width('100px'),
                Tables\Columns\TextColumn::make('debut')
                    ->label('Début')
                    ->time('H:i')
                    ->sortable()
                    ->width('70px'),
                Tables\Columns\TextColumn::make('fin')
                    ->label('Fin')
                    ->time('H:i')
                    ->sortable()
                    ->width('70px'),
                Tables\Columns\TextColumn::make('matiere.unite.nom')
                    ->label('Matière')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('salle.numero')
                    ->label('Salle')
                    ->sortable()
                    ->width('70px')
                    ->badge()
                    ->color('info'),
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
                    ->sortable()
                    ->width('90px'),
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
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter un cours')
                    ->icon('heroicon-o-plus'),
                Tables\Actions\BulkAction::make('create_multiple_courses')
                    ->label('Créer plusieurs cours')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Forms\Components\Repeater::make('cours')
                            ->label('Cours à créer')
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
                            ])
                            ->defaultItems(3)
                            ->addActionLabel('Ajouter un cours')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                $state['matiere_id'] ?
                                CacheService::getMatieres()->find($state['matiere_id'])?->unite?->nom . ' - ' . ($state['jour'] ?? '') :
                                null
                            )
                            ->columns(2),
                    ])
                    ->action(function (array $data) {
                        foreach ($data['cours'] as $coursData) {
                            $this->getOwnerRecord()->cours()->create($coursData);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('update_type')
                        ->label('Modifier le type')
                        ->icon('heroicon-o-pencil')
                        ->form([
                            Forms\Components\Select::make('type')
                                ->label('Nouveau type')
                                ->options([
                                    'cours' => 'Cours',
                                    'td&tp' => 'TD & TP',
                                    'evaluation' => 'Évaluation',
                                    'devoir' => 'Devoir',
                                    'examen' => 'Examen',
                                    'autre' => 'Autre',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['type' => $data['type']]);
                            }
                        }),
                    Tables\Actions\BulkAction::make('update_salle')
                        ->label('Modifier la salle')
                        ->icon('heroicon-o-building-office')
                        ->form([
                            Forms\Components\Select::make('salle_id')
                                ->label('Nouvelle salle')
                                ->options(CacheService::getSalles()->pluck('numero', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['salle_id' => $data['salle_id']]);
                            }
                        }),
                    Tables\Actions\BulkAction::make('update_matiere')
                        ->label('Modifier la matière')
                        ->icon('heroicon-o-academic-cap')
                        ->form([
                            Forms\Components\Select::make('matiere_id')
                                ->label('Nouvelle matière')
                                ->options(function () {
                                    return CacheService::getMatieres()->pluck('unite.nom', 'id');
                                })
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['matiere_id' => $data['matiere_id']]);
                            }
                        }),
                    Tables\Actions\BulkAction::make('update_hours')
                        ->label('Modifier les horaires')
                        ->icon('heroicon-o-clock')
                        ->form([
                            Forms\Components\TimePicker::make('debut')
                                ->label('Nouvelle heure de début'),
                            Forms\Components\TimePicker::make('fin')
                                ->label('Nouvelle heure de fin'),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $updates = [];
                                if (!empty($data['debut'])) {
                                    $updates['debut'] = $data['debut'];
                                }
                                if (!empty($data['fin'])) {
                                    $updates['fin'] = $data['fin'];
                                }
                                if (!empty($updates)) {
                                    $record->update($updates);
                                }
                            }
                        }),
                    Tables\Actions\BulkAction::make('duplicate_courses')
                        ->label('Dupliquer les cours')
                        ->icon('heroicon-o-document-duplicate')
                        ->form([
                            Forms\Components\Select::make('target_jour')
                                ->label('Jour de destination')
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
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->replicate()->fill([
                                    'jour' => $data['target_jour'],
                                ])->save();
                            }
                        }),
                ]),
            ])
            ->defaultSort('jour')
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('Aucun cours programmé')
            ->emptyStateDescription('Commencez par ajouter des cours à cet emploi du temps.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}
