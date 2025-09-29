<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatiereResource\Pages;
use App\Filament\Resources\MatiereResource\RelationManagers;
use App\Models\Matiere;
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
use App\Models\Promotion;

class MatiereResource extends Resource
{
    use TeacherPermissions;

    protected static ?string $model = Matiere::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Académique';

    public static function shouldRegisterNavigation(): bool
    {
        return !(auth()->user() && auth()->user()->role === 'enseignant');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unite_id')
                    ->relationship('unite', 'nom')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('promotion_filter')
                    ->label('Promotion')
                    ->options(fn () => Promotion::query()->orderBy('nom')->pluck('nom', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn (Set $set) => $set('semestre_id', null)),
                Forms\Components\Select::make('semestre_id')
                    ->relationship('semestre', 'slug', modifyQueryUsing: fn (Builder $query, Get $get) => (
                        $get('promotion_filter')
                            ? $query->where('promotion_id', $get('promotion_filter'))
                            : $query
                    ))
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('enseignant_id')
                    ->relationship('enseignant')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->name ?? '—')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with([
                    'unite',
                    'semestre.promotion',
                    'enseignant.user'
                ]);
            })
            ->columns([
                Tables\Columns\TextColumn::make('unite.nom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semestre.slug')
                    ->sortable(),
                Tables\Columns\TextColumn::make('enseignant.user.name')
                    ->label('Enseignant')
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
            'index' => Pages\ListMatieres::route('/'),
            'create' => Pages\CreateMatiere::route('/create'),
            'view' => Pages\ViewMatiere::route('/{record}'),
            'edit' => Pages\EditMatiere::route('/{record}/edit'),
        ];
    }
}
