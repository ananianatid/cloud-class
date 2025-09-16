<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentKeyResource\Pages;
use App\Filament\Resources\EnrollmentKeyResource\RelationManagers;
use App\Models\EnrollmentKey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentKeyResource extends Resource
{
    protected static ?string $model = EnrollmentKey::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('promotion_id')
                    ->relationship('promotion', 'id'),
                Forms\Components\TextInput::make('used_by')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('used_at'),
                Forms\Components\DateTimePicker::make('expires_at'),
                Forms\Components\DateTimePicker::make('revoked_at'),
                Forms\Components\TextInput::make('metadata'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('promotion.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('revoked_at')
                    ->dateTime()
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
            'index' => Pages\ListEnrollmentKeys::route('/'),
            'create' => Pages\CreateEnrollmentKey::route('/create'),
            'view' => Pages\ViewEnrollmentKey::route('/{record}'),
            'edit' => Pages\EditEnrollmentKey::route('/{record}/edit'),
        ];
    }
}
