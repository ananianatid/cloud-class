<?php

namespace App\Filament\Resources\EnrollmentKeyResource\Pages;

use App\Filament\Resources\EnrollmentKeyResource;
use App\Models\EnrollmentKey;
use App\Models\Promotion;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Notifications\Notification;

class ListEnrollmentKeys extends ListRecords
{
    protected static string $resource = EnrollmentKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Créer un token'),
            Actions\Action::make('bulk_create')
                ->label('Créer en lot')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('promotion_id')
                        ->label('Promotion')
                        ->options(Promotion::all()->pluck('nom', 'id'))
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('quantity')
                        ->label('Quantité de tokens')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue(100)
                        ->default(10)
                        ->helperText('Nombre de tokens à créer (maximum 100)'),
                    Forms\Components\DateTimePicker::make('expires_at')
                        ->label('Date d\'expiration (optionnelle)')
                        ->helperText('Laissez vide pour des tokens sans expiration'),
                ])
                ->action(function (array $data): void {
                    // Nettoyer la date d'expiration si elle est vide
                    $expiresAt = !empty($data['expires_at']) ? $data['expires_at'] : null;

                    $tokens = EnrollmentKey::createBulkForPromotion(
                        $data['promotion_id'],
                        $data['quantity'],
                        $expiresAt
                    );

                    Notification::make()
                        ->title('Tokens créés avec succès')
                        ->body($data['quantity'] . ' tokens ont été créés pour la promotion sélectionnée.')
                        ->success()
                        ->send();
                })
                ->modalWidth('md'),
        ];
    }
}
