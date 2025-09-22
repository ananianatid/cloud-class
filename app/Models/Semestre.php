<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semestre extends Model
{
    protected $fillable = [
        'numero',
        'slug',
        'promotion_id',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Get the matieres for the semestre.
     */
    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class);
    }

    /**
     * The promotion this semestre belongs to.
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
}
