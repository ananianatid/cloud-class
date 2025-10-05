<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    protected $fillable = [
        'numero',
        'capacite',
        'en_service',
        'type',
        'description',
    ];

    protected $casts = [
        'en_service' => 'boolean',
    ];

    /**
     * Séances template dans cette salle
     */
    public function seancesTemplate(): HasMany
    {
        return $this->hasMany(SeanceTemplate::class);
    }

    /**
     * Séances occurrences dans cette salle
     */
    public function seancesOccurrences(): HasMany
    {
        return $this->hasMany(SeanceOccurrence::class);
    }

    /**
     * Vérifier si la salle est en service
     */
    public function isEnService(): bool
    {
        return $this->en_service;
    }
}
