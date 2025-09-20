<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemestreResource\Pages;
use App\Filament\Resources\SemestreResource\RelationManagers;
use App\Models\Semestre;
use App\TeacherPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SemestreResource extends Resource
{
    use TeacherPermissions;

    protected static ?string $model = Semestre::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Académique';

    public static function shouldRegisterNavigation(): bool
    {
        return !(auth()->user() && auth()->user()->role === 'enseignant');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $num = preg_replace('/[^0-9]/', '', (string) $state);
                        $set('slug', $num !== '' ? 'semestre-' . $num : '');
                    }),
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('promotion_id')
                    ->relationship('promotion', 'nom')
                    ->required(),
                Forms\Components\DatePicker::make('date_debut')
                    ->required(),
                Forms\Components\DatePicker::make('date_fin')
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
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('promotion.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_debut')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_fin')
                    ->date()
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
            'index' => Pages\ListSemestres::route('/'),
            'create' => Pages\CreateSemestre::route('/create'),
            'view' => Pages\ViewSemestre::route('/{record}'),
            'edit' => Pages\EditSemestre::route('/{record}/edit'),
        ];
    }
}
