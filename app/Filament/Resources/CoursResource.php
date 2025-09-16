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

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Cours';

    protected static ?string $modelLabel = 'Cours';

    protected static ?string $pluralModelLabel = 'Cours';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('promotion_filter')
                    ->label('Promotion (Filtre)')
                    ->options(\App\Models\Promotion::pluck('nom', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('semestre_filter', null))
                    ->afterStateUpdated(fn (callable $set) => $set('emploi_du_temps_id', null))
                    ->dehydrated(false),

                Forms\Components\Select::make('semestre_filter')
                    ->label('Semestre (Filtre)')
                    ->options(function (callable $get) {
                        $promotionId = $get('promotion_filter');
                        if (!$promotionId) {
                            return [];
                        }
                        return \App\Models\Semestre::where('promotion_id', $promotionId)
                            ->pluck('id', 'id')
                            ->mapWithKeys(fn ($id) => ["$id" => "Semestre $id"]);
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('emploi_du_temps_id', null))
                    ->dehydrated(false),

                Forms\Components\Select::make('emploi_du_temps_id')
                    ->label('Emploi du Temps')
                    ->relationship(
                        name: 'emploiDuTemps',
                        titleAttribute: 'nom',
                        modifyQueryUsing: fn ($query, $get) => $query->when(
                            $get('semestre_filter'),
                            fn ($query, $semestreId) => $query->where('semestre_id', $semestreId)
                        )
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('matiere_id')
                    ->label('Matière')
                    ->relationship(
                        name: 'matiere',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn ($query) => $query->with('unite')->orderBy('id')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->unite->nom ?? "Matière #{$record->id}")
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('salle_id')
                    ->label('Salle')
                    ->relationship('salle', 'numero')
                    ->required()
                    ->searchable()
                    ->preload(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emploiDuTemps.nom')
                    ->label('Emploi du Temps')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('matiere.unite.nom')
                    ->label('Matière')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('salle.numero')
                    ->label('Salle')
                    ->sortable()
                    ->searchable(),
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
                    }),
                Tables\Columns\TextColumn::make('debut')
                    ->label('Début')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('fin')
                    ->label('Fin')
                    ->time('H:i'),
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
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('emploi_du_temps_id')
                    ->label('Emploi du Temps')
                    ->relationship('emploiDuTemps', 'nom'),
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
