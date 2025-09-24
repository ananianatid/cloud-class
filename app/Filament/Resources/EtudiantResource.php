<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EtudiantResource\Pages;
use App\Filament\Resources\EtudiantResource\RelationManagers;
use App\Models\Etudiant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EtudiantResource extends Resource
{
    protected static ?string $model = Etudiant::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Ressources humaines';

    public static function shouldRegisterNavigation(): bool
    {
        return !(\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('promotion_id')
                    ->relationship('promotion', 'nom')
                    ->required(),
                Forms\Components\TextInput::make('matricule')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('naissance')
                    ->required(),
                Forms\Components\DatePicker::make('graduation'),
                Forms\Components\TextInput::make('parent')
                    ->maxLength(255),
                Forms\Components\TextInput::make('telephone_parent')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Select::make('statut')
                    ->options([
                        'actif' => 'Actif',
                        'diplome' => 'Diplômé',
                        'suspendu' => 'Suspendu',
                        'abandon' => 'Abandon'
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('promotion.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('matricule')
                    ->searchable(),
                Tables\Columns\TextColumn::make('naissance')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('graduation')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telephone_parent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('statut'),
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
            'index' => Pages\ListEtudiants::route('/'),
            'create' => Pages\CreateEtudiant::route('/create'),
            'view' => Pages\ViewEtudiant::route('/{record}'),
            'edit' => Pages\EditEtudiant::route('/{record}/edit'),
        ];
    }
}
