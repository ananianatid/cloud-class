<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evenement extends Model
{
    protected $fillable = [
        'promotion_id',
        'titre',
        'corps',
        'date',
        'heure',
        'couleur',
        'type',
        'public_cible',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'heure' => 'datetime:H:i',
    ];

    /**
     * Relation avec la promotion
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Relation avec l'utilisateur qui a créé l'événement
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
