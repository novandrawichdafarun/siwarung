<?php

namespace App\Models;

use App\Models\Scopes\WarungScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionLedger extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new WarungScope());
    }

    protected $table = 'commission_ledger';

    protected $fillable = [
        'warung_id',
        'transaction_id',
        'gross_amount',
        'commission_rate',
        'commission_amount',
        'status',
        'settled_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'integer',
            'commission_rate' => 'decimal:4',
            'commission_amount' => 'integer',
            'settled_at' => 'datetime',
        ];
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
