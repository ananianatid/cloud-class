<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeanceOccurrence extends Model
{
    protected $table = 'seances_occurrences';

    protected $fillable = [
        'template_id',
        'date',
        'heure_debut',
        'heure_fin',
        'salle_id',
        'professeur_id',
        'statut',
        'raison_annulation',
        'notes_seance',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    /**
     * Relation avec le template
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(SeanceTemplate::class, 'template_id');
    }

    /**
     * Relation avec la salle
     */
    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    /**
     * Relation avec le professeur
     */
    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }

    /**
     * Présences pour cette occurrence
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'occurrence_id');
    }

    /**
     * Textes pour cette occurrence
     */
    public function textes(): HasMany
    {
        return $this->hasMany(Texte::class, 'occurrence_id');
    }

    /**
     * Vérifier si la séance est planifiée
     */
    public function isPlanifiee(): bool
    {
        return $this->statut === 'planifiée';
    }

    /**
     * Vérifier si la séance est en cours
     */
    public function isEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Vérifier si la séance est terminée
     */
    public function isTerminee(): bool
    {
        return $this->statut === 'terminée';
    }

    /**
     * Vérifier si la séance est annulée
     */
    public function isAnnulee(): bool
    {
        return $this->statut === 'annulée';
    }
}
