<?php

namespace App\Filament\Resources\MatiereResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class FichiersRelationManager extends RelationManager
{
    protected static string $relationship = 'fichiers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->label('Nom du fichier')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: Cours_Algorithmique_Chapitre1.pdf'),
                Forms\Components\FileUpload::make('chemin')
                ->preserveFilenames()
                    ->label('Fichier')
                    ->required()
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/jpeg', 'image/png', 'image/gif', 'text/plain'])
                    ->maxSize(10240) // 10MB
                    ->directory('fichiers-matieres')
                    ->visibility('private')
                    ->helperText('Types acceptés: PDF, Word, Excel, images, texte. Taille max: 10MB')
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        // Remplir automatiquement le nom si le champ nom est vide
                        if ($state && empty($get('nom'))) {
                            // Utiliser le nom du fichier uploadé (qui est déjà le nom original)
                            $set('nom', basename($state));
                        }
                    })
                    ->extraAttributes([
                        'onchange' => '
                            if (this.files && this.files[0] && this.files[0].name) {
                                const nomField = document.querySelector(\'input[name="data[nom]"]\');
                                const nomOriginalField = document.querySelector(\'input[name="data[nom_original]"]\');
                                if (nomField && !nomField.value) {
                                    nomField.value = this.files[0].name;
                                    nomField.dispatchEvent(new Event("input", { bubbles: true }));
                                }
                                if (nomOriginalField) {
                                    nomOriginalField.value = this.files[0].name;
                                    nomOriginalField.dispatchEvent(new Event("input", { bubbles: true }));
                                }
                            }
                        '
                    ]),
                Forms\Components\Select::make('categorie')
                    ->label('Catégorie')
                    ->options([
                        'cours' => 'Cours',
                        'td&tp' => 'TD & TP',
                        'evaluation' => 'Évaluation',
                        'devoir' => 'Devoir',
                        'examen' => 'Examen',
                        'autre' => 'Autre',
                    ])
                    ->required()
                    ->default('cours'),
                Forms\Components\Toggle::make('visible')
                    ->label('Visible pour les étudiants')
                    ->default(true)
                    ->helperText('Décocher pour masquer le fichier aux étudiants'),
                Forms\Components\Hidden::make('ajoute_par')
                    ->default(auth()->id()),
                Forms\Components\Hidden::make('nom_original'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['auteur'])
                    ->orderBy('nom');
            })
            ->recordTitleAttribute('nom')
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom du fichier')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->wrap()
                    ->getStateUsing(function ($record) {
                        // Afficher le nom original si disponible, sinon le nom modifié
                        return $record->nom_original ?: $record->nom;
                    }),
                Tables\Columns\TextColumn::make('categorie')
                    ->label('Catégorie')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cours' => 'success',
                        'td&tp' => 'info',
                        'evaluation' => 'warning',
                        'devoir' => 'warning',
                        'examen' => 'danger',
                        'autre' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cours' => 'Cours',
                        'td&tp' => 'TD & TP',
                        'evaluation' => 'Évaluation',
                        'devoir' => 'Devoir',
                        'examen' => 'Examen',
                        'autre' => 'Autre',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('chemin')
                    ->label('Fichier')
                    ->formatStateUsing(function ($state) {
                        $filename = basename($state);
                        return strlen($filename) > 30 ? substr($filename, 0, 30) . '...' : $filename;
                    })
                    ->tooltip(function ($record) {
                        return basename($record->chemin);
                    })
                    ->color('info'),
                Tables\Columns\TextColumn::make('auteur.name')
                    ->label('Ajouté par')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        return $record->auteur->name ?? 'Utilisateur #' . $record->ajoute_par;
                    }),
                Tables\Columns\IconColumn::make('visible')
                    ->label('Visible')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ajouté le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categorie')
                    ->label('Catégorie')
                    ->options([
                        'cours' => 'Cours',
                        'td&tp' => 'TD & TP',
                        'evaluation' => 'Évaluation',
                        'devoir' => 'Devoir',
                        'examen' => 'Examen',
                        'autre' => 'Autre',
                    ]),
                Tables\Filters\TernaryFilter::make('visible')
                    ->label('Visibilité')
                    ->placeholder('Tous les fichiers')
                    ->trueLabel('Visibles seulement')
                    ->falseLabel('Masqués seulement'),
                Tables\Filters\SelectFilter::make('ajoute_par')
                    ->label('Ajouté par')
                    ->options(function () {
                        return \App\Models\User::whereIn('id', function ($query) {
                            $query->select('ajoute_par')
                                ->from('fichiers')
                                ->where('matiere_id', $this->getOwnerRecord()->id);
                        })->pluck('name', 'id');
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter un fichier')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Si le nom n'est pas rempli, utiliser le nom du fichier uploadé
                        if (empty($data['nom']) && !empty($data['chemin'])) {
                            $data['nom'] = basename($data['chemin']);
                        }
                        // S'assurer que nom_original est défini
                        if (empty($data['nom_original']) && !empty($data['chemin'])) {
                            $data['nom_original'] = basename($data['chemin']);
                        }
                        // S'assurer que ajoute_par est défini
                        if (empty($data['ajoute_par'])) {
                            $data['ajoute_par'] = auth()->id();
                        }
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Télécharger')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function ($record) {
                        if (Storage::exists($record->chemin)) {
                            // Utiliser le nom original pour le téléchargement
                            $nomTelechargement = $record->nom_original ?: $record->nom;
                            return Storage::download($record->chemin, $nomTelechargement);
                        }
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('update_categorie')
                        ->label('Modifier la catégorie')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('categorie')
                                ->label('Nouvelle catégorie')
                                ->options([
                                    'cours' => 'Cours',
                                    'td&tp' => 'TD & TP',
                                    'evaluation' => 'Évaluation',
                                    'devoir' => 'Devoir',
                                    'examen' => 'Examen',
                                    'autre' => 'Autre',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['categorie' => $data['categorie']]);
                            }
                        }),
                    Tables\Actions\BulkAction::make('toggle_visibility')
                        ->label('Basculer la visibilité')
                        ->icon('heroicon-o-eye')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['visible' => !$record->visible]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('nom')
            ->emptyStateHeading('Aucun fichier')
            ->emptyStateDescription('Commencez par ajouter des fichiers à cette matière.')
            ->emptyStateIcon('heroicon-o-document');
    }
}
