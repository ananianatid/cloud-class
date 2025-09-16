<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'numero',
        'capacite',
        'en_service',
        'type',
    ];

    protected $casts = [
        'en_service' => 'boolean',
    ];
}
