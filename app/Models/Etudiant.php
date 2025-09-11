<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
