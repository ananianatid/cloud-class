<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evenement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titre',
        'corps',
        'date',
        'heure',
        'couleur',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'heure' => 'datetime:H:i',
    ];

    /**
     * Get the user who created the evenement.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the formatted date and time.
     */
    public function getFormattedDateTimeAttribute(): string
    {
        $date = $this->date->format('d/m/Y');
        if ($this->heure) {
            $time = $this->heure->format('H:i');
            return "{$date} à {$time}";
        }
        return $date;
    }

    /**
     * Get the default color if none is set.
     */
    public function getCouleurAttribute($value): string
    {
        return $value ?: '#10B981'; // Vert par défaut
    }
}
