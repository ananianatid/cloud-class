<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmploiDuTemps extends Model
{
    protected $fillable = [
        'semestre_id',
        'nom',
        'categorie',
        'actif',
        'debut',
        'fin',
        'descrpition',
    ];

    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }

    /**
     * Get the cours for the emploi du temps.
     */
    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class);
    }

    /**
     * Get the cours count for the emploi du temps.
     */
    public function getCoursCountAttribute(): int
    {
        return $this->cours()->count();
    }

    /**
     * Get the cours grouped by day.
     */
    public function getCoursByDayAttribute(): array
    {
        return $this->cours()
            ->with(['matiere.unite', 'salle'])
            ->orderBy('jour')
            ->orderBy('debut')
            ->get()
            ->groupBy('jour')
            ->toArray();
    }
}
