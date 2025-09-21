<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TeacherScheduleWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = Auth::user();

        // Vérifier si c'est un enseignant
        if (!$user || $user->role !== 'enseignant') {
            return $table->query(\App\Models\Cours::query()->whereRaw('1 = 0'));
        }

        $enseignant = \App\Models\Enseignant::where('user_id', $user->id)->first();

        if (!$enseignant) {
            return $table->query(\App\Models\Cours::query()->whereRaw('1 = 0'));
        }

        return $table
            ->query(
                \App\Models\Cours::query()
                    ->whereHas('matiere', function($q) use ($enseignant) {
                        $q->where('enseignant_id', $enseignant->id);
                    })
                    ->whereHas('emploiDuTemps', function($q) {
                        $q->where('actif', true);
                    })
                    ->with(['matiere.unite', 'matiere.semestre.promotion', 'emploiDuTemps', 'salle'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('matiere.unite.nom')
                    ->label('Matière')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('matiere.semestre.promotion.nom')
                    ->label('Promotion')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('matiere.semestre.slug')
                    ->label('Semestre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jour')
                    ->label('Jour')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('debut')
                    ->label('Heure Début')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fin')
                    ->label('Heure Fin')
                    ->sortable(),

                Tables\Columns\TextColumn::make('salle.numero')
                    ->label('Salle')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cours' => 'success',
                        'td' => 'info',
                        'tp' => 'warning',
                        'examen' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('emploiDuTemps.nom')
                    ->label('Emploi du Temps')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('jour')
            ->paginated(false);
    }
}
