<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Filament\Resources\PromotionResource\RelationManagers;
use App\Models\Promotion;
use App\Models\Diplome;
use App\Models\Filiere;
use App\Services\CacheService;
use App\TeacherPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromotionResource extends Resource
{
    use TeacherPermissions;

    protected static ?string $model = Promotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static ?string $navigationGroup = 'Académique';

    public static function shouldRegisterNavigation(): bool
    {
        return !(\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->disabled()
                    ->dehydrated(true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('diplome_id')
                    ->options(CacheService::getDiplomes()->pluck('nom', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('nom', self::makePromotionName(
                            $get('diplome_id'),
                            $get('filiere_id'),
                            $get('annee_debut'),
                            $get('annee_fin'),
                        ));
                    })
                    ->required(),
                Forms\Components\Select::make('filiere_id')
                    ->options(CacheService::getFilieres()->pluck('nom', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('nom', self::makePromotionName(
                            $get('diplome_id'),
                            $get('filiere_id'),
                            $get('annee_debut'),
                            $get('annee_fin'),
                        ));
                    })
                    ->required(),
                Forms\Components\TextInput::make('annee_debut')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('nom', self::makePromotionName(
                            $get('diplome_id'),
                            $get('filiere_id'),
                            $get('annee_debut'),
                            $get('annee_fin'),
                        ));
                    })
                    ->required()
                    ->rules(['required', 'integer', 'min:2000', 'max:2100'])
                    ->validationMessages([
                        'min' => 'L\'année de début doit être supérieure ou égale à 2000',
                        'max' => 'L\'année de début doit être inférieure ou égale à 2100',
                    ]),
                Forms\Components\TextInput::make('annee_fin')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('nom', self::makePromotionName(
                            $get('diplome_id'),
                            $get('filiere_id'),
                            $get('annee_debut'),
                            $get('annee_fin'),
                        ));
                    })
                    ->required()
                    ->rules(['required', 'integer', 'min:2000', 'max:2100', 'gte:annee_debut'])
                    ->validationMessages([
                        'min' => 'L\'année de fin doit être supérieure ou égale à 2000',
                        'max' => 'L\'année de fin doit être inférieure ou égale à 2100',
                        'gte' => 'L\'année de fin doit être supérieure ou égale à l\'année de début',
                    ]),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
            ]);
    }

    private static function makePromotionName(?int $diplomeId, ?int $filiereId, ?string $anneeDebut, ?string $anneeFin): string
    {
        if (!$diplomeId || !$filiereId || !$anneeDebut || !$anneeFin) {
            return '';
        }

        $diplome = Diplome::find($diplomeId);
        $filiere = Filiere::find($filiereId);

        $diplomeNom = $diplome?->nom ?? '';
        $filiereCode = $filiere?->code ?? '';

        // Validation des années
        $anneeDebutInt = (int) $anneeDebut;
        $anneeFinInt = (int) $anneeFin;

        if ($anneeDebutInt < 2000 || $anneeDebutInt > 2100 ||
            $anneeFinInt < 2000 || $anneeFinInt > 2100 ||
            $anneeFinInt < $anneeDebutInt) {
            return '';
        }

        $yy = function ($year) {
            $digits = preg_replace('/[^0-9]/', '', (string) $year);
            return substr($digits, -2);
        };

        $start = $yy($anneeDebut);
        $end = $yy($anneeFin);

        if ($diplomeNom === '' || $filiereCode === '' || $start === '' || $end === '') {
            return '';
        }

        // Format amélioré : Diplôme-Filière-AnnéeDébut-AnnéeFin
        return sprintf('%s-%s-%s-%s',
            strtoupper($diplomeNom),
            strtoupper($filiereCode),
            $start,
            $end
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['diplome', 'filiere'])
                    ->orderBy('annee_fin', 'desc')
                    ->orderBy('annee_debut', 'desc');
            })
            ->defaultSort('annee_fin', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('diplome.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('filiere.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('annee_debut')
                    ->label('Année de début')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('annee_fin')
                    ->label('Année de fin')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duree')
                    ->label('Durée (années)')
                    ->getStateUsing(fn ($record) => $record->duree . ' an(s)')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Statut')
                    ->getStateUsing(fn ($record) => $record->isActive())
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('active_etudiants_count')
                    ->label('Étudiants actifs')
                    ->counts('etudiants')
                    ->sortable(),
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Promotions actives')
                    ->queries(
                        true: fn ($query) => $query->active(),
                        false: fn ($query) => $query->where(function ($q) {
                            $currentYear = now()->year;
                            $q->where('annee_debut', '>', $currentYear)
                              ->orWhere('annee_fin', '<', $currentYear);
                        }),
                    ),
                Tables\Filters\SelectFilter::make('annee_fin')
                    ->label('Année de fin')
                    ->options(function () {
                        $annees = Promotion::distinct()->pluck('annee_fin')->sort()->values();
                        return $annees->mapWithKeys(fn($annee) => [$annee => $annee]);
                    })
                    ->multiple(),
                Tables\Filters\SelectFilter::make('diplome_id')
                    ->label('Diplôme')
                    ->relationship('diplome', 'nom'),
                Tables\Filters\SelectFilter::make('filiere_id')
                    ->label('Filière')
                    ->relationship('filiere', 'nom'),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'view' => Pages\ViewPromotion::route('/{record}'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
