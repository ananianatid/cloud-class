<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenceJustifiee extends Model
{
    protected $table = 'absences_justifiees';

    protected $fillable = [
        'etudiant_id',
        'date_debut',
        'date_fin',
        'motif',
        'justificatif',
        'statut',
        'traitee_par',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Relation avec l'étudiant
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    /**
     * Relation avec l'utilisateur qui a traité l'absence
     */
    public function traiteePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traitee_par');
    }

    /**
     * Vérifier si l'absence est en attente
     */
    public function isEnAttente(): bool
    {
        return $this->statut === 'En attente';
    }

    /**
     * Vérifier si l'absence est approuvée
     */
    public function isApprouvee(): bool
    {
        return $this->statut === 'Approuvée';
    }

    /**
     * Vérifier si l'absence est rejetée
     */
    public function isRejetee(): bool
    {
        return $this->statut === 'Rejetée';
    }
}
