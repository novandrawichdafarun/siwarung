<?php

namespace App\Models;

use App\Models\Scopes\WarungScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new WarungScope());
    }

    protected $fillable = [
        'warung_id',
        'user_id',
        'total_gross',
        'commission_rate',
        'commission_amount',
        'total_net',
        'payment_method',
        'payment_status',
        'midtrans_order_id',
        'midtrans_snap_token',
        'paid_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'total_gross' => 'integer',
            'commission_rate' => 'decimal:4',
            'commission_amount' => 'integer',
            'total_net' => 'integer',
            'paid_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class);
    }

    /**
     * Kasir yang memproses transaksi ini.
     */
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function commissionLedger(): HasOne
    {
        return $this->hasOne(CommissionLedger::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'transaction_id', 'id');
    }

    // =========================================================
    // HELPER METHODS
    // =========================================================

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->payment_status === 'cancelled';
    }

    public function isQris(): bool
    {
        return $this->payment_method === 'qris';
    }

    public function isCash(): bool
    {
        return $this->payment_method === 'cash';
    }

    // =========================================================
    // LOCAL SCOPES
    // =========================================================

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('paid_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year);
    }
}
