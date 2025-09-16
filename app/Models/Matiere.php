<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    protected $fillable = [
        'unite_id',
        'semestre_id',
        'enseignant_id',
    ];

    /**
     * Get the semestre that owns the matiere.
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }

    /**
     * Get the unite d'enseignement this matiere belongs to.
     */
    public function unite(): BelongsTo
    {
        return $this->belongsTo(UniteEnseignement::class, 'unite_id');
    }

    /**
     * Get the enseignant responsible for this matiere.
     */
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    /**
     * Accessor: return the unite name (unite->nom).
     */
    public function getUniteNomAttribute(): string
    {
        return $this->unite->nom ?? '';
    }

    /**
     * Files associated with this matiere.
     */
    public function fichiers(): HasMany
    {
        return $this->hasMany(Fichier::class);
    }
}
