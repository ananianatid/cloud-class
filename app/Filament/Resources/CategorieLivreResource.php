<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategorieLivreResource\Pages;
use App\Filament\Resources\CategorieLivreResource\RelationManagers;
use App\Models\CategorieLivre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

class CategorieLivreResource extends Resource
{
    protected static ?string $model = CategorieLivre::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Catégories de livres';

    protected static ?string $modelLabel = 'Catégorie';

    protected static ?string $pluralModelLabel = 'Catégories';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nom')
                    ->label('Nom de la catégorie')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: Informatique, Littérature, Sciences...'),

                Textarea::make('description')
                    ->label('Description')
                    ->maxLength(1000)
                    ->rows(3)
                    ->placeholder('Description optionnelle de la catégorie...'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('livres_count')
                    ->label('Nombre de livres')
                    ->counts('livres')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Supprimer la catégorie')
                    ->modalDescription('Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action supprimera également tous les livres associés.')
                    ->modalSubmitActionLabel('Oui, supprimer'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Supprimer les catégories sélectionnées')
                        ->modalDescription('Êtes-vous sûr de vouloir supprimer ces catégories ? Cette action supprimera également tous les livres associés.')
                        ->modalSubmitActionLabel('Oui, supprimer'),
                ]),
            ])
            ->defaultSort('nom', 'asc');
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
            'index' => Pages\ListCategorieLivres::route('/'),
            'create' => Pages\CreateCategorieLivre::route('/create'),
            'edit' => Pages\EditCategorieLivre::route('/{record}/edit'),
        ];
    }
}
