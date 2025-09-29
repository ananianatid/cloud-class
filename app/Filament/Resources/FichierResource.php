<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FichierResource\Pages;
use App\Filament\Resources\FichierResource\RelationManagers;
use App\Models\Fichier;
use App\Models\Promotion;
use App\Models\Semestre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FichierResource extends Resource
{
    protected static ?string $model = Fichier::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Contenu';

    public static function form(Form $form): Form
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $isTeacher = $user && $user->role === 'enseignant';

        return $form
            ->schema([
                Forms\Components\Select::make('promotion_filter')
                    ->label('Promotion')
                    ->options(fn () => Promotion::query()->orderBy('nom')->pluck('nom', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn (Set $set) => $set('semestre_filter', null))
                    ->visible(!$isTeacher), // Masquer pour les enseignants
                Forms\Components\Select::make('semestre_filter')
                    ->label('Semestre')
                    ->options(fn (Get $get) => Semestre::query()
                        ->when($get('promotion_filter'), fn ($q, $promotionId) => $q->where('promotion_id', $promotionId))
                        ->orderBy('slug')
                        ->pluck('slug', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn (Set $set) => $set('matiere_id', null))
                    ->visible(!$isTeacher), // Masquer pour les enseignants
                Forms\Components\Select::make('matiere_id')
                    ->label("Unité d'enseignement")
                    ->options(function (Get $get) {
                        $query = \App\Models\Matiere::query();

                        // Si c'est un enseignant, filtrer seulement ses matières
                        if (\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant') {
                            $enseignantId = session('current_enseignant_id');
                            if ($enseignantId) {
                                $query->where('enseignant_id', $enseignantId);
                            }
                        } else {
                            // Pour les admins, utiliser le filtre semestre
                            if ($get('semestre_filter')) {
                                $query->where('semestre_id', $get('semestre_filter'));
                            }
                        }

                        // Eager load les relations nécessaires
                        $matieres = $query->with(['unite', 'semestre.promotion'])->get();

                        // Map: [matiere_id => "Unité - Promotion - Semestre"]
                        return $matieres->mapWithKeys(function ($matiere) {
                            $uniteNom = $matiere->unite->nom ?? 'Unité inconnue';
                            $promotionNom = $matiere->semestre->promotion->nom ?? 'Promotion inconnue';
                            $semestreSlug = $matiere->semestre->slug ?? 'Semestre inconnu';

                            $label = "{$uniteNom} - {$promotionNom} - {$semestreSlug}";
                            return [$matiere->id => $label];
                        })->filter();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        // Mettre à jour les informations de la matière sélectionnée
                        if ($state) {
                            $matiere = \App\Models\Matiere::with(['unite', 'semestre.promotion'])->find($state);
                            if ($matiere) {
                                $set('matiere_info', [
                                    'unite' => $matiere->unite->nom ?? 'Unité inconnue',
                                    'promotion' => $matiere->semestre->promotion->nom ?? 'Promotion inconnue',
                                    'semestre' => $matiere->semestre->slug ?? 'Semestre inconnu',
                                ]);
                            }
                        }
                    }),
                Forms\Components\Placeholder::make('matiere_info_display')
                    ->label('Informations de la matière sélectionnée')
                    ->content(function (Get $get) {
                        $matiereInfo = $get('matiere_info');
                        if (!$matiereInfo) {
                            return 'Sélectionnez une matière pour voir les détails';
                        }

                        return "📚 **Unité :** {$matiereInfo['unite']}\n" .
                               "🎓 **Promotion :** {$matiereInfo['promotion']}\n" .
                               "📅 **Semestre :** {$matiereInfo['semestre']}";
                    })
                    ->visible(fn () => \Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant'),
                Forms\Components\FileUpload::make('chemin')
                    ->directory('fichiers')
                    // ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->afterStateUpdated(fn ($state, Set $set) => $set('nom', basename((string) $state)))
                    ->storeFileNamesIn('nom')
                    ->required(),
                Forms\Components\TextInput::make('nom')
                    ->disabled()
                    ->dehydrated(true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('categorie')
                    ->options([
                        'cours' => 'cours',
                        'td&tp' => 'td&tp',
                        'evaluation' => 'evaluation',
                        'devoir' => 'devoir',
                        'examen' => 'examen',
                        'autre' => 'autre',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('visible')
                    ->default(true)
                    ->required(),
                Forms\Components\Hidden::make('ajoute_par')
                    ->default(fn () => \Illuminate\Support\Facades\Auth::id())
                    ->dehydrated(true),
                Forms\Components\Hidden::make('matiere_info')
                    ->dehydrated(false), // Ne pas sauvegarder en base
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Eager loading pour optimiser les performances
                $query->with([
                    'matiere.unite',
                    'matiere.semestre.promotion',
                    'auteur'
                ]);

                // Si c'est un enseignant, filtrer seulement les fichiers de ses matières
                if (\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant') {
                    $enseignantId = session('current_enseignant_id');
                    if ($enseignantId) {
                        $query->whereHas('matiere', function ($q) use ($enseignantId) {
                            $q->where('enseignant_id', $enseignantId);
                        });
                    }
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('matiere.unite.nom')
                    ->label('Unité')
                    ->sortable(),
                Tables\Columns\TextColumn::make('matiere.semestre.promotion.nom')
                    ->label('Promotion')
                    ->sortable()
                    ->visible(fn () => \Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant'),
                Tables\Columns\TextColumn::make('matiere.semestre.slug')
                    ->label('Semestre')
                    ->sortable()
                    ->visible(fn () => \Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'enseignant'),
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categorie'),
                Tables\Columns\IconColumn::make('visible')
                    ->boolean(),
                Tables\Columns\TextColumn::make('auteur.name')
                    ->label('Ajouté par')
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
            'index' => Pages\ListFichiers::route('/'),
            'create' => Pages\CreateFichier::route('/create'),
            'view' => Pages\ViewFichier::route('/{record}'),
            'edit' => Pages\EditFichier::route('/{record}/edit'),
        ];
    }
}
