<?php

namespace App\Models;

use App\Models\Scopes\WarungScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new WarungScope());
    }

    protected $fillable = [
        'warung_id',
        'product_id',
        'user_id',
        'transaction_id',
        'type',
        'quantity',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'stok_sebelum' => 'integer',
            'stok_sesudah' => 'integer',
            'transaction_id' => 'integer',
        ];
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke transaksi tanpa FK constraint di database.
     * Menggunakan belongsTo biasa, hasilnya bisa null.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // =========================================================
    // LOCAL SCOPES
    // =========================================================

    public function scopeIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }
}
