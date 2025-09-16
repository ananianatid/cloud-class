<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enseignant extends Model
{
    /**
     * Get the user that owns the enseignant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
