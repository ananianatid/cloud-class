<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RoleUser extends Model
{
    protected $table = 'role_user';

    protected $fillable = [
        'role_id',
        'user_id',
        'user_type',
    ];

    /**
     * Relation avec le rôle
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation polymorphe avec le type d'utilisateur
     */
    public function userType(): MorphTo
    {
        return $this->morphTo('user_type', 'user_id');
    }
}
