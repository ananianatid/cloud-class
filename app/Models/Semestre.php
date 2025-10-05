<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semestre extends Model
{
    protected $fillable = [
        'promotion_id',
        'numero',
        'slug',
        'debut',
        'fin',
    ];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date',
    ];

    /**
     * Relation avec la promotion
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Matières de ce semestre
     */
    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class);
    }

    /**
     * Emplois du temps de ce semestre
     */
    public function emploisDuTemps(): HasMany
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    /**
     * Cahiers de texte de ce semestre
     */
    public function cahierTextes(): HasMany
    {
        return $this->hasMany(CahierTexte::class);
    }

    /**
     * Cahiers de présence de ce semestre
     */
    public function cahierPresences(): HasMany
    {
        return $this->hasMany(CahierPresence::class);
    }
}
