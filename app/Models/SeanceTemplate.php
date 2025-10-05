<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeanceTemplate extends Model
{
    protected $table = 'seances_template';

    protected $fillable = [
        'emploi_id',
        'matiere_id',
        'salle_id',
        'professeur_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'type',
        'modalite',
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    /**
     * Relation avec l'emploi du temps
     */
    public function emploi(): BelongsTo
    {
        return $this->belongsTo(EmploiDuTemps::class, 'emploi_id');
    }

    /**
     * Relation avec la matière
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Relation avec la salle
     */
    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    /**
     * Relation avec le professeur
     */
    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }

    /**
     * Occurrences générées à partir de ce template
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(SeanceOccurrence::class, 'template_id');
    }
}
