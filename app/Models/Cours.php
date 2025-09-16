<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cours extends Model
{
    protected $fillable = [
        'emploi_du_temps_id',
        'matiere_id',
        'salle_id',
        'jour',
        'debut',
        'fin',
        'type',
    ];

    protected $casts = [
        'debut' => 'datetime:H:i',
        'fin' => 'datetime:H:i',
    ];

    /**
     * Get the emploi du temps that owns the cours.
     */
    public function emploiDuTemps(): BelongsTo
    {
        return $this->belongsTo(EmploiDuTemps::class);
    }

    /**
     * Get the matiere that owns the cours.
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Get the salle that owns the cours.
     */
    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }
}
