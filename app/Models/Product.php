<?php

namespace App\Models;

use App\Models\Scopes\WarungScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new WarungScope());
    }

    protected $fillable = [
        'warung_id',
        'category_id',
        'nama_produk',
        'deskripsi',
        'foto',
        'harga_jual',
        'harga_beli',
        'stok',
        'stok_minimal',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga_jual' => 'integer',
            'harga_beli' => 'integer',
            'stok' => 'integer',
            'stok_minimal' => 'integer',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    // =========================================================
    // HELPER METHODS
    // =========================================================

    /**
     * Cek apakah stok sudah di bawah atau sama dengan stok minimal.
     * Dipakai untuk menampilkan alert di dashboard.
     */
    public function isLowStock(): bool
    {
        return $this->stok <= $this->stok_minimal;
    }

    /**
     * Cek apakah stok masih cukup untuk qty tertentu.
     * Dipakai sebelum memproses transaksi.
     */
    public function hasEnoughStock(int $qty): bool
    {
        return $this->stok >= $qty;
    }

    /**
     * Format harga jual ke Rupiah untuk tampilan Blade.
     * Contoh: 15000 → "Rp 15.000"
     */
    public function getFormattedHargaJualAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    // =========================================================
    // LOCAL SCOPES — query builder shortcuts
    // =========================================================

    /**
     * Produk yang aktif dan belum dihapus.
     * Penggunaan: Product::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Produk yang stoknya di bawah minimal.
     * Penggunaan: Product::lowStock()->get()
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimal');
    }
}
