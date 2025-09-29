<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmploiDuTempsResource\Pages\CreateEmploiDuTemps;
use App\Filament\Resources\EmploiDuTempsResource\Pages\EditEmploiDuTemps;
use App\Filament\Resources\EmploiDuTempsResource\Pages\ListEmploiDuTemps;
use App\Filament\Resources\EmploiDuTempsResource\Pages\ViewEmploiDuTemps;
use App\Filament\Resources\EmploiDuTempsResource\RelationManagers;
use App\Models\EmploiDuTemps;
use App\Services\CacheService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use App\Models\Promotion;
use App\Models\Semestre;

class EmploiDuTempsResource extends Resource
{
    protected static ?string $model = EmploiDuTemps::class;

    public static function shouldRegisterNavigation(): bool
    {
        return !(\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant');
    }

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Temps';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('promotion_id')
                    ->options(CacheService::getPromotions()->pluck('nom', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function (Set $set) {
                        $set('semestre_id', null);
                    })
                    ->required(),
                Select::make('semestre_id')
                    ->options(function (Get $get) {
                        $promotionId = $get('promotion_id');
                        if (!$promotionId) {
                            return [];
                        }
                        return CacheService::getSemestresByPromotion($promotionId)
                            ->pluck('slug', 'id');
                    })
                    ->required()
                    ->label('Semestre')
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $semestre = Semestre::with('promotion')->find($get('semestre_id'));
                        $categorie = $get('categorie');
                        if ($semestre && $semestre->promotion && $categorie) {
                            $set('nom', Str::slug($semestre->promotion->nom . '-' . $semestre->slug . '-' . $categorie));
                        }
                    }),
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(true)
                    ->unique(ignoreRecord: true),
                Select::make('categorie')
                    ->options([
                        'principal' => 'Principal',
                        'examen' => 'Examen',
                        'devoir' => 'Devoir',
                        'mission' => 'Mission',
                        'autre' => 'Autre',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $semestre = Semestre::with('promotion')->find($get('semestre_id'));
                        $categorie = $get('categorie');
                        if ($semestre && $semestre->promotion && $categorie) {
                            $set('nom', Str::slug($semestre->promotion->nom . '-' . $semestre->slug . '-' . $categorie));
                        }
                    }),
                Forms\Components\Toggle::make('actif')
                    ->required(),
                Forms\Components\DatePicker::make('debut')
                    ->required(),
                Forms\Components\DatePicker::make('fin')
                    ->required(),
                Forms\Components\TextInput::make('descrpition')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['semestre.promotion', 'cours'])
                    ->withCount('cours')
                    ->orderBy('actif', 'desc')
                    ->orderBy('debut', 'desc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('semestre.promotion.nom')
                    ->label('Promotion')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semestre.slug')
                    ->label('Semestre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categorie')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'principal' => 'success',
                        'examen' => 'danger',
                        'devoir' => 'warning',
                        'mission' => 'info',
                        'autre' => 'gray',
                    }),
                Tables\Columns\IconColumn::make('actif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('cours_count')
                    ->label('Nb Cours')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('debut')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fin')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descrpition')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('categorie')
                    ->options([
                        'principal' => 'Principal',
                        'examen' => 'Examen',
                        'devoir' => 'Devoir',
                        'mission' => 'Mission',
                        'autre' => 'Autre',
                    ]),
                Tables\Filters\TernaryFilter::make('actif')
                    ->label('Actif')
                    ->placeholder('Tous')
                    ->trueLabel('Actifs seulement')
                    ->falseLabel('Inactifs seulement'),
                Tables\Filters\SelectFilter::make('semestre.promotion_id')
                    ->label('Promotion')
                    ->relationship('semestre.promotion', 'nom'),
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
            RelationManagers\CoursRelationManager::class,
            RelationManagers\WeeklyCoursRelationManager::class,
            RelationManagers\CalendarCoursRelationManager::class,
            RelationManagers\QuickEditCoursRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmploiDuTemps::route('/'),
            'create' => CreateEmploiDuTemps::route('/create'),
            'view' => ViewEmploiDuTemps::route('/{record}'),
            'edit' => EditEmploiDuTemps::route('/{record}/edit'),
        ];
    }
}
