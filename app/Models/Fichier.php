<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fichier extends Model
{
    protected $fillable = [
        'matiere_id',
        'ajoute_par',
        'chemin',
        'nom',
        'nom_original',
        'categorie',
        'visible',
        'taille',
        'telechargements',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'telechargements' => 'integer',
    ];

    /**
     * Relation avec la matière
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Relation avec l'utilisateur qui a ajouté le fichier
     */
    public function ajoutePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ajoute_par');
    }
}
