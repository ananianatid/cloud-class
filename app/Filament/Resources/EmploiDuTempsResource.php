<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmploiDuTempsResource\Pages\CreateEmploiDuTemps;
use App\Filament\Resources\EmploiDuTempsResource\Pages\EditEmploiDuTemps;
use App\Filament\Resources\EmploiDuTempsResource\Pages\ListEmploiDuTemps;
use App\Filament\Resources\EmploiDuTempsResource\Pages\ViewEmploiDuTemps;
use App\Filament\Resources\EmploiDuTempsResource\RelationManagers;
use App\Models\EmploiDuTemps;
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

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Temps';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('promotion_id')
                    ->options(Promotion::pluck('nom', 'id'))
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
                        return Semestre::where('promotion_id', $promotionId)
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
                Tables\Columns\TextColumn::make('categorie'),
                Tables\Columns\IconColumn::make('actif')
                    ->boolean(),
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
            'index' => ListEmploiDuTemps::route('/'),
            'create' => CreateEmploiDuTemps::route('/create'),
            'view' => ViewEmploiDuTemps::route('/{record}'),
            'edit' => EditEmploiDuTemps::route('/{record}/edit'),
        ];
    }
}
