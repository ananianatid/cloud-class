<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $navigationLabel = 'Utilisateurs';
    protected static ?string $modelLabel = 'Utilisateur';
    protected static ?string $pluralModelLabel = 'Utilisateurs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('E-mail vérifié le'),
                Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('two_factor_secret')
                    ->label('Secret 2FA')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('two_factor_recovery_codes')
                    ->label('Codes de récupération 2FA')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('two_factor_confirmed_at')
                    ->label('2FA confirmé le'),
                Forms\Components\TextInput::make('current_team_id')
                    ->label('Équipe actuelle')
                    ->numeric(),
                Forms\Components\TextInput::make('profile_photo_path')
                    ->label('Photo de profil (chemin)')
                    ->maxLength(2048),
                Forms\Components\TextInput::make('telephone')
                    ->label('Téléphone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('role')
                    ->label('Rôle')
                    ->required(),
                Forms\Components\TextInput::make('sexe')
                    ->label('Sexe')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('E-mail vérifié le')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('two_factor_confirmed_at')
                    ->label('2FA confirmé le')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_team_id')
                    ->label('Équipe actuelle')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('profile_photo_path')
                    ->label('Photo de profil (chemin)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Rôle'),
                Tables\Columns\TextColumn::make('sexe')
                    ->label('Sexe'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Voir'),
                Tables\Actions\EditAction::make()
                    ->label('Modifier'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
