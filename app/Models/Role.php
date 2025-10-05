<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'nom',
        'nom_affichage',
        'description',
    ];

    /**
     * Relations avec les utilisateurs
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')
                    ->withPivot('user_type')
                    ->withTimestamps();
    }

    /**
     * Relations avec les permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
                    ->withTimestamps();
    }

    /**
     * Clés d'inscription pour ce rôle
     */
    public function clesInscription(): HasMany
    {
        return $this->hasMany(CleInscription::class);
    }
}
