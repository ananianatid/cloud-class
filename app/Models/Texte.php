<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Texte extends Model
{
    protected $fillable = [
        'occurrence_id',
        'contenu',
        'devoirs',
    ];

    /**
     * Relation avec l'occurrence de séance
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(SeanceOccurrence::class, 'occurrence_id');
    }
}
