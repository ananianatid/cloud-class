<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EnrollmentKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'promotion_id',
        'used_by',
        'used_at',
        'expires_at',
        'revoked_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => AsArrayObject::class,
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function isUsable(): bool
    {
        if ($this->revoked_at !== null) {
            return false;
        }

        if ($this->used_at !== null) {
            return false;
        }

        if ($this->expires_at !== null && now()->greaterThan($this->expires_at)) {
            return false;
        }

        return $this->promotion_id !== null;
    }

    /**
     * Génère une clé unique pour l'inscription
     */
    public static function generateUniqueKey(): string
    {
        do {
            $key = 'ETU-' . strtoupper(Str::random(8));
        } while (static::where('key', $key)->exists());

        return $key;
    }

    /**
     * Crée plusieurs tokens d'inscription pour une promotion
     */
    public static function createBulkForPromotion(int $promotionId, int $quantity, $expiresAt = null): array
    {
        $tokens = [];

        // Conversion de la date d'expiration si nécessaire
        $expiresAtDate = null;
        if ($expiresAt !== null) {
            if (is_string($expiresAt)) {
                try {
                    $expiresAtDate = \Carbon\Carbon::parse($expiresAt);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException("Format de date invalide : {$expiresAt}");
                }
            } elseif ($expiresAt instanceof CarbonInterface) {
                $expiresAtDate = $expiresAt;
            }
        }

        for ($i = 0; $i < $quantity; $i++) {
            $tokens[] = static::create([
                'key' => static::generateUniqueKey(),
                'promotion_id' => $promotionId,
                'expires_at' => $expiresAtDate,
            ]);
        }

        return $tokens;
    }
}

