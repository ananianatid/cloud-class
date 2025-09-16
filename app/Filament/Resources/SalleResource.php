<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalleResource\Pages;
use App\Filament\Resources\SalleResource\RelationManagers;
use App\Models\Salle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalleResource extends Resource
{
    protected static ?string $model = Salle::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Infrastructure';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('capacite')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('en_service')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacite')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('en_service')
                    ->boolean(),
                Tables\Columns\TextColumn::make('type'),
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
            'index' => Pages\ListSalles::route('/'),
            'create' => Pages\CreateSalle::route('/create'),
            'view' => Pages\ViewSalle::route('/{record}'),
            'edit' => Pages\EditSalle::route('/{record}/edit'),
        ];
    }
}
