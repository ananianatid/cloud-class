<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etudiant extends Model
{
    protected $fillable = [
        'user_id',
        'promotion_id',
        'matricule',
        'naissance',
        'graduation',
        'parent',
        'telephone_parent',
        'statut',
    ];

    protected $casts = [
        'naissance' => 'date',
        'graduation' => 'date',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la promotion
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Présences de l'étudiant
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Notes de l'étudiant
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Absences justifiées de l'étudiant
     */
    public function absencesJustifiees(): HasMany
    {
        return $this->hasMany(AbsenceJustifiee::class);
    }

    /**
     * Vérifier si l'étudiant est actif
     */
    public function isActif(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifier si l'étudiant est diplômé
     */
    public function isDiplome(): bool
    {
        return $this->statut === 'gradue';
    }
}
