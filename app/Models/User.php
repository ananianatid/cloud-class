<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relations avec les rôles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
                    ->withPivot('user_type')
                    ->withTimestamps();
    }

    /**
     * Relations avec les permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id')
                    ->withPivot('user_type')
                    ->withTimestamps();
    }

    /**
     * Relation avec l'étudiant
     */
    public function etudiant(): HasOne
    {
        return $this->hasOne(Etudiant::class);
    }

    /**
     * Relation avec le professeur
     */
    public function professeur(): HasOne
    {
        return $this->hasOne(Professeur::class);
    }

    /**
     * Relation avec le responsable
     */
    public function responsable(): HasOne
    {
        return $this->hasOne(Responsable::class);
    }

    /**
     * Clés d'inscription créées par cet utilisateur
     */
    public function clesInscriptionCreees(): HasMany
    {
        return $this->hasMany(CleInscription::class, 'created_by');
    }

    /**
     * Clés d'inscription utilisées par cet utilisateur
     */
    public function clesInscriptionUtilisees(): HasMany
    {
        return $this->hasMany(CleInscription::class, 'used_by');
    }

    /**
     * Clés d'inscription révoquées par cet utilisateur
     */
    public function clesInscriptionRevokees(): HasMany
    {
        return $this->hasMany(CleInscription::class, 'revoked_by');
    }

    /**
     * Événements créés par cet utilisateur
     */
    public function evenements(): HasMany
    {
        return $this->hasMany(Evenement::class, 'created_by');
    }

    /**
     * Fichiers ajoutés par cet utilisateur
     */
    public function fichiers(): HasMany
    {
        return $this->hasMany(Fichier::class, 'ajoute_par');
    }

    /**
     * Absences justifiées traitées par cet utilisateur
     */
    public function absencesJustifieesTraitees(): HasMany
    {
        return $this->hasMany(AbsenceJustifiee::class, 'traitee_par');
    }
}
