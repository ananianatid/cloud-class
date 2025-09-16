<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FichierResource\Pages;
use App\Filament\Resources\FichierResource\RelationManagers;
use App\Models\Fichier;
use App\Models\Promotion;
use App\Models\Semestre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FichierResource extends Resource
{
    protected static ?string $model = Fichier::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Contenu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('promotion_filter')
                    ->label('Promotion')
                    ->options(fn () => Promotion::query()->orderBy('nom')->pluck('nom', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn (Set $set) => $set('semestre_filter', null)),
                Forms\Components\Select::make('semestre_filter')
                    ->label('Semestre')
                    ->options(fn (Get $get) => Semestre::query()
                        ->when($get('promotion_filter'), fn ($q, $promotionId) => $q->where('promotion_id', $promotionId))
                        ->orderBy('slug')
                        ->pluck('slug', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn (Set $set) => $set('matiere_id', null)),
                Forms\Components\Select::make('matiere_id')
                    ->relationship('matiere', 'id', modifyQueryUsing: fn (Builder $query, Get $get) => (
                        $get('semestre_filter')
                            ? $query->where('semestre_id', $get('semestre_filter'))
                            : $query
                    ))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\FileUpload::make('chemin')
                    ->directory('fichiers')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->afterStateUpdated(fn ($state, Set $set) => $set('nom', basename((string) $state)))
                    ->required(),
                Forms\Components\TextInput::make('nom')
                    ->disabled()
                    ->dehydrated(true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('categorie')
                    ->options([
                        'cours' => 'cours',
                        'td&tp' => 'td&tp',
                        'evaluation' => 'evaluation',
                        'devoir' => 'devoir',
                        'examen' => 'examen',
                        'autre' => 'autre',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('visible')
                    ->default(true)
                    ->required(),
                Forms\Components\Hidden::make('ajoute_par')
                    ->default(fn () => \Illuminate\Support\Facades\Auth::id())
                    ->dehydrated(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('matiere.id')
                    ->label('Matière')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categorie'),
                Tables\Columns\IconColumn::make('visible')
                    ->boolean(),
                Tables\Columns\TextColumn::make('auteur.name')
                    ->label('Ajouté par')
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
            'index' => Pages\ListFichiers::route('/'),
            'create' => Pages\CreateFichier::route('/create'),
            'view' => Pages\ViewFichier::route('/{record}'),
            'edit' => Pages\EditFichier::route('/{record}/edit'),
        ];
    }
}
