<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvenementResource\Pages;
use App\Filament\Resources\EvenementResource\RelationManagers;
use App\Models\Evenement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EvenementResource extends Resource
{
    protected static ?string $model = Evenement::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Événements';

    protected static ?string $modelLabel = 'Événement';

    protected static ?string $pluralModelLabel = 'Événements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titre')
                    ->required()
                    ->maxLength(191)
                    ->label('Titre')
                    ->placeholder('Titre de l\'événement'),
                Forms\Components\RichEditor::make('corps')
                    ->required()
                    ->columnSpanFull()
                    ->label('Description')
                    ->placeholder('Description de l\'événement (Markdown supporté)')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'link',
                        'bulletList',
                        'orderedList',
                        'h2',
                        'h3',
                        'blockquote',
                    ]),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->label('Date')
                    ->default(now()),
                Forms\Components\TimePicker::make('heure')
                    ->label('Heure (optionnel)')
                    ->seconds(false),
                Forms\Components\ColorPicker::make('couleur')
                    ->required()
                    ->label('Couleur')
                    ->default('#10B981')
                    ->helperText('Couleur d\'affichage dans le calendrier'),
                Forms\Components\Select::make('created_by')
                    ->relationship('createdBy', 'name')
                    ->required()
                    ->label('Créé par')
                    ->default(auth()->user()?->id)
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titre')
                    ->searchable()
                    ->sortable()
                    ->label('Titre')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Date'),
                Tables\Columns\TextColumn::make('heure')
                    ->time('H:i')
                    ->label('Heure')
                    ->placeholder('Aucune heure'),
                Tables\Columns\ColorColumn::make('couleur')
                    ->label('Couleur'),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Créé par')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Créé le'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('Du'),
                        Forms\Components\DatePicker::make('date_until')
                            ->label('Au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
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
            'index' => Pages\ListEvenements::route('/'),
            'create' => Pages\CreateEvenement::route('/create'),
            'edit' => Pages\EditEvenement::route('/{record}/edit'),
        ];
    }
}
