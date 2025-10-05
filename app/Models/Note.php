<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'etudiant_id',
        'note',
        'note_sur',
        'evaluation_id',
        'commentaire',
        'rattrapage',
        'note_rattrapage',
    ];

    protected $casts = [
        'note' => 'decimal:2',
        'note_sur' => 'decimal:2',
        'note_rattrapage' => 'decimal:2',
        'rattrapage' => 'boolean',
    ];

    /**
     * Relation avec l'étudiant
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    /**
     * Relation avec l'évaluation
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Calculer le pourcentage de la note
     */
    public function getPourcentageAttribute(): float
    {
        return ($this->note / $this->note_sur) * 100;
    }

    /**
     * Vérifier si c'est une note de rattrapage
     */
    public function isRattrapage(): bool
    {
        return $this->rattrapage;
    }
}
