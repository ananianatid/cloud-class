<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class TeacherMatieresWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = Auth::user();

        // Vérifier si c'est un enseignant
        if (!$user || $user->role !== 'enseignant') {
            return $table->query(\App\Models\Matiere::query()->whereRaw('1 = 0'));
        }

        $enseignant = \App\Models\Enseignant::where('user_id', $user->id)->first();

        if (!$enseignant) {
            return $table->query(\App\Models\Matiere::query()->whereRaw('1 = 0'));
        }

        return $table
            ->query(
                \App\Models\Matiere::query()
                    ->where('enseignant_id', $enseignant->id)
                    ->with(['unite', 'semestre.promotion'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('unite.nom')
                    ->label('Unité d\'Enseignement')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('semestre.promotion.nom')
                    ->label('Promotion')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('semestre.slug')
                    ->label('Semestre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('semestre.date_debut')
                    ->label('Début Semestre')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('semestre.date_fin')
                    ->label('Fin Semestre')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fichiers_count')
                    ->label('Fichiers')
                    ->counts('fichiers')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->getStateUsing(function ($record) {
                        return $record->semestre->date_debut <= now() &&
                               $record->semestre->date_fin >= now();
                    })
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
            ])
            ->defaultSort('unite.nom')
            ->paginated(false);
    }
}
