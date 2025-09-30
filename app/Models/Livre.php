<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Livre extends Model
{
    protected $fillable = [
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

}
