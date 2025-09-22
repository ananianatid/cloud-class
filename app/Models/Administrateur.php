<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrateur extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'niveau',
        'statut',
        'departement',
        'permissions',
        'derniere_connexion',
        'telephone_bureau',
        'notes'
    ];

    protected $casts = [
        'permissions' => 'array',
        'derniere_connexion' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->niveau === 'super_admin';
    }

    public function isActive(): bool
    {
        return $this->statut === 'actif';
    }
}
