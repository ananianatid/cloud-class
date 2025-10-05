<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livre extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'categorie_id',
        'titre',
        'isbn',
        'chemin_fichier',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieLivre::class);
    }
}
