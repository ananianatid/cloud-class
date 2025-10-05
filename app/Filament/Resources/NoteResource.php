<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Filament\Resources\NoteResource\RelationManagers;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestion académique';
    protected static ?int $navigationSort = 8;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('etudiant_id')
                    ->relationship('etudiant', 'id')
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('note_sur')
                    ->required()
                    ->numeric()
                    ->default(20.00),
                Forms\Components\Select::make('evaluation_id')
                    ->relationship('evaluation', 'id')
                    ->required(),
                Forms\Components\Textarea::make('commentaire')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('rattrapage')
                    ->required(),
                Forms\Components\TextInput::make('note_rattrapage')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('etudiant.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note_sur')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('rattrapage')
                    ->boolean(),
                Tables\Columns\TextColumn::make('note_rattrapage')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'view' => Pages\ViewNote::route('/{record}'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }
}
