<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diplome extends Model
{
    protected $fillable = [
        'nom',
        'duree',
        'code',
        'description',
    ];

    /**
     * Promotions de ce diplôme
     */
    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }
}
