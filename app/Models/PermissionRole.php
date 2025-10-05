<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionRole extends Model
{
    protected $table = 'permission_role';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * Relation avec le rôle
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relation avec la permission
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
