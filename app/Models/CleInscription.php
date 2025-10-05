<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CleInscription extends Model
{
    protected $table = 'cle_inscription';

    protected $fillable = [
        'token',
        'used_by',
        'role_id',
        'promotion_id',
        'revoked_by',
        'created_by',
        'used_at',
        'expires_at',
        'revoked_at',
        'revoked_reason',
        'status',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur qui a utilisé la clé
     */
    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Relation avec le rôle
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relation avec la promotion
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Relation avec l'utilisateur qui a révoqué la clé
     */
    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    /**
     * Relation avec l'utilisateur qui a créé la clé
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Vérifier si la clé est active
     */
    public function isActive(): bool
    {
        return $this->status === 'actif' &&
               ($this->expires_at === null || $this->expires_at > now());
    }

    /**
     * Vérifier si la clé est expirée
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at <= now();
    }

    /**
     * Vérifier si la clé est utilisée
     */
    public function isUsed(): bool
    {
        return $this->status === 'utilisé';
    }

    /**
     * Vérifier si la clé est révoquée
     */
    public function isRevoked(): bool
    {
        return $this->status === 'révoqué';
    }
}
