<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PermissionUser extends Model
{
    protected $table = 'permission_user';

    protected $fillable = [
        'user_id',
        'permission_id',
        'user_type',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la permission
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Relation polymorphe avec le type d'utilisateur
     */
    public function userType(): MorphTo
    {
        return $this->morphTo('user_type', 'user_id');
    }
}
