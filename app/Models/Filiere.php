<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    protected $fillable = [
        'nom',
        'code',
        'description',
    ];

    /**
     * Promotions de cette filière
     */
    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }
}
