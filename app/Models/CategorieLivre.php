<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieLivre extends Model
{
    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Relation avec les livres
     */
    public function livres(): HasMany
    {
        return $this->hasMany(Livre::class);
    }
}
