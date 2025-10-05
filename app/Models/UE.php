<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UE extends Model
{
    protected $table = 'ues';

    protected $fillable = [
        'nom',
        'code',
        'description',
        'credits',
    ];

    /**
     * Matières de cette UE
     */
    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class, 'ue_id');
    }
}
