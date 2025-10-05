<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CahierPresence extends Model
{
    protected $table = 'cahier_presences';

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
