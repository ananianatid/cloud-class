<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CahierTexte extends Model
{
    protected $table = 'cahier_textes';

    protected $fillable = [
        'semestre_id',
    ];

    /**
     * Relation avec le semestre
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }
}
