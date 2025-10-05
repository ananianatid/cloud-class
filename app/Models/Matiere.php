<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    protected $fillable = [
        'intitule',
        'ue_id',
        'semestre_id',
        'professeur_id',
        'volume_horaire_cm',
        'is_optionnelle',
    ];

    protected $casts = [
        'is_optionnelle' => 'boolean',
    ];

    /**
     * Relation avec l'UE
     */
    public function ue(): BelongsTo
    {
        return $this->belongsTo(UE::class);
    }

    /**
     * Relation avec le semestre
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }

    /**
     * Relation avec le professeur
     */
    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }

    /**
     * Séances template de cette matière
     */
    public function seancesTemplate(): HasMany
    {
        return $this->hasMany(SeanceTemplate::class);
    }

    /**
     * Fichiers de cette matière
     */
    public function fichiers(): HasMany
    {
        return $this->hasMany(Fichier::class);
    }

    /**
     * Évaluations de cette matière
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Vérifier si la matière est optionnelle
     */
    public function isOptionnelle(): bool
    {
        return $this->is_optionnelle;
    }
}
