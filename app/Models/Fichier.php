<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fichier extends Model
{
    protected $guarded = [];

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ajoute_par');
    }
}
