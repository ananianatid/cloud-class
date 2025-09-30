<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LivreResource\Pages;
use App\Filament\Resources\LivreResource\RelationManagers;
use App\Models\Livre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LivreResource extends Resource
{
    protected static ?string $model = Livre::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Bibliothèque';

    public static function shouldRegisterNavigation(): bool
    {
        return !(\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('chemin_fichier')
                    ->label('Fichier du livre')
                    ->required()
                    ->acceptedFileTypes(['application/pdf', 'application/epub+zip', 'text/plain'])
                    ->maxSize(10240) // 10MB
                    ->directory('livres')
                    ->rules([
                        'file',
                        'mimes:pdf,epub,txt',
                        'max:10240'
                    ]),
                Forms\Components\TextInput::make('isbn')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('ISBN'),
                Forms\Components\Select::make('categorie_livre_id')
                    ->relationship('categorieLivre', 'nom')
                    ->required()
                    ->label('Catégorie'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('isbn')
                    ->searchable()
                    ->sortable()
                    ->label('ISBN'),
                Tables\Columns\TextColumn::make('categorieLivre.nom')
                    ->searchable()
                    ->sortable()
                    ->label('Catégorie'),
                Tables\Columns\TextColumn::make('chemin_fichier')
                    ->label('Fichier')
                    ->formatStateUsing(fn (string $state): string => basename($state))
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
                Tables\Filters\SelectFilter::make('categorie_livre_id')
                    ->relationship('categorieLivre', 'nom')
                    ->label('Catégorie'),
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
            'index' => Pages\ListLivres::route('/'),
            'create' => Pages\CreateLivre::route('/create'),
            'view' => Pages\ViewLivre::route('/{record}'),
            'edit' => Pages\EditLivre::route('/{record}/edit'),
        ];
    }
}
