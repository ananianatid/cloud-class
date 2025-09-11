<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentKey extends Model
{
    //
}
