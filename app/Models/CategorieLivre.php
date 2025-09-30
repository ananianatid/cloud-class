<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieLivre extends Model
{
    protected $fillable = [
        'nom',
        'description'
    ];

    public function livres()
    {
        return $this->hasMany(Livre::class, 'categorie_livre_id');
    }
}
