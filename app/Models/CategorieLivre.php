<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategorieLivre extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nom', 'description'];

    public function livres()
    {
        return $this->hasMany(Livre::class);
    }
}
