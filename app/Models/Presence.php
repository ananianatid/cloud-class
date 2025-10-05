<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    protected $fillable = [
        'occurrence_id',
        'etudiant_id',
        'present',
        'justifiee',
        'motif_absence',
    ];

    protected $casts = [
        'present' => 'boolean',
        'justifiee' => 'boolean',
    ];

    /**
     * Relation avec l'occurrence de séance
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(SeanceOccurrence::class, 'occurrence_id');
    }

    /**
     * Relation avec l'étudiant
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    /**
     * Vérifier si l'étudiant est présent
     */
    public function isPresent(): bool
    {
        return $this->present;
    }

    /**
     * Vérifier si l'absence est justifiée
     */
    public function isJustifiee(): bool
    {
        return $this->justifiee;
    }
}
