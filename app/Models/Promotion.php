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
        'statut',
    ];

    protected $casts = [
        'annee_debut' => 'integer',
        'annee_fin' => 'integer',
        'statut' => 'string',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Promotion $promotion) {
            static::validatePromotion($promotion);
            static::updateStatut($promotion);
        });

        static::updating(function (Promotion $promotion) {
            static::validatePromotion($promotion);
            static::updateStatut($promotion);
        });
    }

    /**
     * Validate promotion data
     */
    private static function validatePromotion(Promotion $promotion): void
    {
        // Validation des années
        if ($promotion->annee_debut < 2000 || $promotion->annee_debut > 2100) {
            throw new \InvalidArgumentException('L\'année de début doit être entre 2000 et 2100');
        }

        if ($promotion->annee_fin < 2000 || $promotion->annee_fin > 2100) {
            throw new \InvalidArgumentException('L\'année de fin doit être entre 2000 et 2100');
        }

        if ($promotion->annee_fin < $promotion->annee_debut) {
            throw new \InvalidArgumentException('L\'année de fin doit être supérieure ou égale à l\'année de début');
        }

        // Vérifier l'unicité
        $existing = static::where('diplome_id', $promotion->diplome_id)
            ->where('filiere_id', $promotion->filiere_id)
            ->where('annee_debut', $promotion->annee_debut)
            ->where('annee_fin', $promotion->annee_fin)
            ->where('id', '!=', $promotion->id ?? 0)
            ->exists();

        if ($existing) {
            throw new \InvalidArgumentException('Une promotion avec cette combinaison diplôme/filière/années existe déjà');
        }
    }

    /**
     * Mettre à jour le statut de la promotion basé sur les années
     */
    private static function updateStatut(Promotion $promotion): void
    {
        $currentYear = now()->year;

        if ($currentYear >= $promotion->annee_debut && $currentYear <= $promotion->annee_fin) {
            $promotion->statut = 'actif';
        } else {
            $promotion->statut = 'archive';
        }
    }

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

    /**
     * Get the diplome name.
     */
    public function getDiplomeNameAttribute(): string
    {
        return $this->diplome->nom ?? '';
    }

    /**
     * Get the duration of the promotion in years.
     */
    public function getDureeAttribute(): int
    {
        return $this->annee_fin - $this->annee_debut + 1;
    }

    /**
     * Check if the promotion is currently active.
     */
    public function isActive(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Check if the promotion has ended.
     */
    public function hasEnded(): bool
    {
        return $this->statut === 'archive';
    }

    /**
     * Get the full name with filiere and diplome.
     */
    public function getFullNameAttribute(): string
    {
        return $this->diplome_name . ' ' . $this->filiere_name . ' ' . $this->annee_debut . '-' . $this->annee_fin;
    }

    /**
     * Scope to get active promotions.
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope to get archived promotions.
     */
    public function scopeArchived($query)
    {
        return $query->where('statut', 'archive');
    }

    /**
     * Scope to get promotions by year.
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('annee_debut', '<=', $year)
                    ->where('annee_fin', '>=', $year);
    }

    /**
     * Mettre à jour le statut de toutes les promotions
     */
    public static function updateAllStatuts(): int
    {
        $currentYear = now()->year;
        $updated = 0;

        // Mettre à jour les promotions actives
        $updated += static::where('annee_debut', '<=', $currentYear)
            ->where('annee_fin', '>=', $currentYear)
            ->where('statut', '!=', 'actif')
            ->update(['statut' => 'actif']);

        // Mettre à jour les promotions archivées
        $updated += static::where(function ($query) use ($currentYear) {
            $query->where('annee_debut', '>', $currentYear)
                  ->orWhere('annee_fin', '<', $currentYear);
        })
        ->where('statut', '!=', 'archive')
        ->update(['statut' => 'archive']);

        return $updated;
    }
}
