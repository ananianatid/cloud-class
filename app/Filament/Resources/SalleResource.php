<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalleResource\Pages;
use App\Filament\Resources\SalleResource\RelationManagers;
use App\Models\Salle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalleResource extends Resource
{
    protected static ?string $model = Salle::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Infrastructure';

    public static function shouldRegisterNavigation(): bool
    {
        return !(\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero')
                    ->label('Numéro de salle')
                    ->required()
                    ->placeholder('Ex: A-101, B-202, C-301')
                    ->helperText('Format recommandé: Bâtiment-Numéro (ex: A-101)'),
                Forms\Components\TextInput::make('capacite')
                    ->label('Capacité')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(500)
                    ->placeholder('Nombre de places')
                    ->helperText('Nombre maximum d\'étudiants que peut accueillir la salle'),
                Forms\Components\Select::make('type')
                    ->label('Type de salle')
                    ->required()
                    ->options([
                        'cours' => 'Salle de cours',
                        'td_tp' => 'Salle TD/TP',
                        'laboratoire' => 'Laboratoire informatique',
                        'examen' => 'Salle d\'examen',
                        'reunion' => 'Salle de réunion',
                        'conference' => 'Salle de conférence',
                        'atelier' => 'Atelier pratique',
                        'studio' => 'Studio multimédia',
                        'amphi' => 'Amphithéâtre',
                        'informatique' => 'Salle informatique',
                    ])
                    ->searchable()
                    ->placeholder('Sélectionner un type de salle'),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Description de la salle, équipements, particularités...')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('en_service')
                    ->label('En service')
                    ->default(true)
                    ->helperText('Décocher si la salle est en maintenance ou indisponible'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cours' => 'success',
                        'td_tp' => 'info',
                        'laboratoire' => 'warning',
                        'examen' => 'danger',
                        'reunion' => 'gray',
                        'conference' => 'purple',
                        'atelier' => 'orange',
                        'studio' => 'pink',
                        'amphi' => 'blue',
                        'informatique' => 'cyan',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cours' => 'Salle de cours',
                        'td_tp' => 'TD/TP',
                        'laboratoire' => 'Laboratoire',
                        'examen' => 'Examen',
                        'reunion' => 'Réunion',
                        'conference' => 'Conférence',
                        'atelier' => 'Atelier',
                        'studio' => 'Studio',
                        'amphi' => 'Amphithéâtre',
                        'informatique' => 'Informatique',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacite')
                    ->label('Capacité')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('secondary')
                    ->suffix(' places'),
                Tables\Columns\IconColumn::make('en_service')
                    ->label('En service')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('description')
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
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type de salle')
                    ->options([
                        'cours' => 'Salle de cours',
                        'td_tp' => 'TD/TP',
                        'laboratoire' => 'Laboratoire informatique',
                        'examen' => 'Salle d\'examen',
                        'reunion' => 'Salle de réunion',
                        'conference' => 'Salle de conférence',
                        'atelier' => 'Atelier pratique',
                        'studio' => 'Studio multimédia',
                        'amphi' => 'Amphithéâtre',
                        'informatique' => 'Salle informatique',
                    ])
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('en_service')
                    ->label('En service')
                    ->placeholder('Toutes les salles')
                    ->trueLabel('En service seulement')
                    ->falseLabel('En maintenance seulement'),
                Tables\Filters\Filter::make('capacite_min')
                    ->form([
                        Forms\Components\TextInput::make('capacite_min')
                            ->label('Capacité minimum')
                            ->numeric()
                            ->placeholder('Ex: 30'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['capacite_min'],
                                fn (Builder $query, $capacite): Builder => $query->where('capacite', '>=', $capacite),
                            );
                    }),
                Tables\Filters\Filter::make('capacite_max')
                    ->form([
                        Forms\Components\TextInput::make('capacite_max')
                            ->label('Capacité maximum')
                            ->numeric()
                            ->placeholder('Ex: 100'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['capacite_max'],
                                fn (Builder $query, $capacite): Builder => $query->where('capacite', '<=', $capacite),
                            );
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
                    Tables\Actions\BulkAction::make('toggle_service')
                        ->label('Basculer le statut de service')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['en_service' => !$record->en_service]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('numero')
            ->emptyStateHeading('Aucune salle')
            ->emptyStateDescription('Commencez par créer votre première salle.')
            ->emptyStateIcon('heroicon-o-building-office');
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
            'index' => Pages\ListSalles::route('/'),
            'create' => Pages\CreateSalle::route('/create'),
            'view' => Pages\ViewSalle::route('/{record}'),
            'edit' => Pages\EditSalle::route('/{record}/edit'),
        ];
    }
}
