<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'matiere_id',
        'nom',
        'date',
        'type',
        'pourcentage',
    ];

    protected $casts = [
        'date' => 'date',
        'pourcentage' => 'decimal:2',
    ];

    /**
     * Relation avec la matière
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Notes de cette évaluation
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
