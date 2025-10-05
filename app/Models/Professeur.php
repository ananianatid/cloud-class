<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Professeur extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'specialite',
        'statut',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Matières enseignées par le professeur
     */
    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class);
    }

    /**
     * Séances template du professeur
     */
    public function seancesTemplate(): HasMany
    {
        return $this->hasMany(SeanceTemplate::class);
    }

    /**
     * Séances occurrences du professeur
     */
    public function seancesOccurrences(): HasMany
    {
        return $this->hasMany(SeanceOccurrence::class);
    }

    /**
     * Vérifier si le professeur est actif
     */
    public function isActif(): bool
    {
        return $this->statut === 'actif';
    }
}
