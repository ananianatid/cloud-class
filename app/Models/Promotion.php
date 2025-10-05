<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    protected $fillable = [
        'diplome_id',
        'filiere_id',
        'nom',
        'debut',
        'fin',
        'description',
        'statut',
        'responsable_id',
    ];

    protected $casts = [
        'debut' => 'integer',
        'fin' => 'integer',
    ];

    /**
     * Relation avec le diplôme
     */
    public function diplome(): BelongsTo
    {
        return $this->belongsTo(Diplome::class);
    }

    /**
     * Relation avec la filière
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    /**
     * Relation avec le responsable
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class);
    }

    /**
     * Étudiants de cette promotion
     */
    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    /**
     * Semestres de cette promotion
     */
    public function semestres(): HasMany
    {
        return $this->hasMany(Semestre::class);
    }

    /**
     * Événements de cette promotion
     */
    public function evenements(): HasMany
    {
        return $this->hasMany(Evenement::class);
    }

    /**
     * Clés d'inscription pour cette promotion
     */
    public function clesInscription(): HasMany
    {
        return $this->hasMany(CleInscription::class);
    }

    /**
     * Vérifier si la promotion est active
     */
    public function isActive(): bool
    {
        return $this->statut === 'actif';
    }
}
