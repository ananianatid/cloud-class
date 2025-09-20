<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Filament\Resources\PromotionResource\RelationManagers;
use App\Models\Promotion;
use App\Models\Diplome;
use App\Models\Filiere;
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
        return !(auth()->user() && auth()->user()->role === 'enseignant');
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
                    ->relationship('diplome', 'nom')
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
                    ->relationship('filiere', 'nom')
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
                Forms\Components\TextInput::make('annee_fin')
                    ->numeric()
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

        $yy = function ($year) {
            $digits = preg_replace('/[^0-9]/', '', (string) $year);
            return substr($digits, -2);
        };

        $start = $yy($anneeDebut);
        $end = $yy($anneeFin);

        if ($diplomeNom === '' || $filiereCode === '' || $start === '' || $end === '') {
            return '';
        }

        return sprintf('%s-%s-%s-%s', $diplomeNom, $filiereCode, $start, $end);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('diplome.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('filiere.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('annee_debut'),
                Tables\Columns\TextColumn::make('annee_fin'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'view' => Pages\ViewPromotion::route('/{record}'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
