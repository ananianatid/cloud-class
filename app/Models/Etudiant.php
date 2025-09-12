<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'promotion_id',
        'matricule',
        'naissance',
        'graduatin',
        'parent',
        'telephone_parent',
        'statut',
    ];

    /**
     * Get the user that owns the etudiant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the promotion that the etudiant belongs to.
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Get the full name of the etudiant from the user.
     */
    public function getFullNameAttribute(): string
    {
        return $this->user->name ?? '';
    }

    /**
     * Get the email of the etudiant from the user.
     */
    public function getEmailAttribute(): string
    {
        return $this->user->email ?? '';
    }

    /**
     * Get the promotion name.
     */
    public function getPromotionNameAttribute(): string
    {
        return $this->promotion->nom ?? '';
    }
}
