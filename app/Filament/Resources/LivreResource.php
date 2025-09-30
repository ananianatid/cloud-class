<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LivreResource\Pages;
use App\Filament\Resources\LivreResource\RelationManagers;
use App\Models\Livre;
use App\Models\CategorieLivre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;

class LivreResource extends Resource
{
    protected static ?string $model = Livre::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Livres';

    protected static ?string $modelLabel = 'Livre';

    protected static ?string $pluralModelLabel = 'Livres';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('isbn')
                    ->label('ISBN')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Ex: 9791035503376')
                    ->helperText('L\'ISBN sera utilisé pour récupérer automatiquement les informations du livre via Google Books'),

                FileUpload::make('chemin_fichier')
                    ->label('Fichier du livre')
                    ->required()
                    ->acceptedFileTypes(['application/pdf', 'application/epub+zip'])
                    ->directory('livres')
                    ->visibility('private')
                    ->helperText('Formats acceptés: PDF, EPUB'),

                Select::make('categorie_livre_id')
                    ->label('Catégorie')
                    ->options(CategorieLivre::all()->pluck('nom', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('nom')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                    ])
                    ->createOptionUsing(function (array $data): int {
                        return CategorieLivre::create($data)->getKey();
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Couverture')
                    ->getStateUsing(function (Livre $record) {
                        return $record->image_url;
                    })
                    ->defaultImageUrl(asset('images/no-book-cover.png'))
                    ->size(60),

                TextColumn::make('titre')
                    ->label('Titre')
                    ->getStateUsing(function (Livre $record) {
                        return $record->titre;
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('auteur')
                    ->label('Auteur')
                    ->getStateUsing(function (Livre $record) {
                        return $record->auteur;
                    })
                    ->searchable(),

                TextColumn::make('isbn')
                    ->label('ISBN')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('categorie.nom')
                    ->label('Catégorie')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ajouté le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categorie_livre_id')
                    ->label('Catégorie')
                    ->relationship('categorie', 'nom')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Action::make('view_google_books')
                    ->label('Voir sur Google Books')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(function (Livre $record) {
                        $info = $record->getGoogleBooksInfo();
                        return $info['infoLink'] ?? '#';
                    })
                    ->openUrlInNewTab()
                    ->visible(fn (Livre $record) => $record->getGoogleBooksInfo() !== null),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListLivres::route('/'),
            'create' => Pages\CreateLivre::route('/create'),
            'edit' => Pages\EditLivre::route('/{record}/edit'),
        ];
    }
}
