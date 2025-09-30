<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Livre extends Model
{
    protected $fillable = [
        'nom',
        'isbn',
        'chemin_fichier',
        'categorie_livre_id',
    ];

    /**
     * Relation avec la catégorie
     */
    public function categorieLivre(): BelongsTo
    {
        return $this->belongsTo(CategorieLivre::class);
    }

    /**
     * Accessor pour obtenir le nom du fichier sans extension
     */
    public function getNomFichierAttribute(): string
    {
        return pathinfo($this->chemin_fichier, PATHINFO_FILENAME);
    }

    /**
     * Accessor pour obtenir l'extension du fichier
     */
    public function getExtensionFichierAttribute(): string
    {
        return pathinfo($this->chemin_fichier, PATHINFO_EXTENSION);
    }
}
