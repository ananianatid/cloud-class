<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'diplome_id',
        'filiere_id',
        'annee_debut',
        'annee_fin',
        'description',
    ];

    /**
     * Get the filiere that owns the promotion.
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    /**
     * Get the diplome that owns the promotion.
     */
    public function diplome(): BelongsTo
    {
        return $this->belongsTo(Diplome::class);
    }

    /**
     * Get the etudiants for the promotion.
     */
    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    /**
     * Get the enrollment keys for the promotion.
     */
    public function enrollmentKeys(): HasMany
    {
        return $this->hasMany(EnrollmentKey::class);
    }

    /**
     * Get the count of active etudiants in the promotion.
     */
    public function getActiveEtudiantsCountAttribute(): int
    {
        return $this->etudiants()->where('statut', 'actif')->count();
    }

    /**
     * Get the filiere name.
     */
    public function getFiliereNameAttribute(): string
    {
        return $this->filiere->nom ?? '';
    }
}
