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

class WeeklyCoursRelationManager extends RelationManager
{
    protected static string $relationship = 'cours';

    protected static ?string $title = 'Vue Hebdomadaire des Cours';

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
                    ->sortable()
                    ->width('120px'),
                Tables\Columns\TextColumn::make('debut')
                    ->label('Début')
                    ->time('H:i')
                    ->sortable()
                    ->width('80px'),
                Tables\Columns\TextColumn::make('fin')
                    ->label('Fin')
                    ->time('H:i')
                    ->sortable()
                    ->width('80px'),
                Tables\Columns\TextColumn::make('matiere.unite.nom')
                    ->label('Matière')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('salle.numero')
                    ->label('Salle')
                    ->sortable()
                    ->width('80px'),
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
                    ->width('100px'),
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
                Tables\Actions\BulkAction::make('create_weekly_template')
                    ->label('Créer un modèle hebdomadaire')
                    ->icon('heroicon-o-calendar-days')
                    ->form([
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
                        Forms\Components\TimePicker::make('debut')
                            ->label('Heure de début')
                            ->required(),
                        Forms\Components\TimePicker::make('fin')
                            ->label('Heure de fin')
                            ->required(),
                        Forms\Components\CheckboxList::make('jours')
                            ->label('Jours de la semaine')
                            ->options([
                                'lundi' => 'Lundi',
                                'mardi' => 'Mardi',
                                'mercredi' => 'Mercredi',
                                'jeudi' => 'Jeudi',
                                'vendredi' => 'Vendredi',
                                'samedi' => 'Samedi',
                                'dimanche' => 'Dimanche',
                            ])
                            ->required()
                            ->columns(4),
                    ])
                    ->action(function (array $data) {
                        foreach ($data['jours'] as $jour) {
                            $this->getOwnerRecord()->cours()->create([
                                'matiere_id' => $data['matiere_id'],
                                'salle_id' => $data['salle_id'],
                                'jour' => $jour,
                                'debut' => $data['debut'],
                                'fin' => $data['fin'],
                                'type' => $data['type'],
                            ]);
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
