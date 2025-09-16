<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploiDuTemps extends Model
{
    protected $fillable = [
        'semestre_id',
        'nom',
        'categorie',
        'actif',
        'debut',
        'fin',
        'descrpition',
    ];

    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }


}
