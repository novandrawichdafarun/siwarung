<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Tidak ada WarungScope — diakses selalu melalui relasi Transaction
// Tidak ada SoftDeletes — cascade ikut transaksi induknya
class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'nama_snapshot',
        'harga_snapshot',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'harga_snapshot' => 'integer',
            'quantity'       => 'integer',
            'subtotal'       => 'integer',
        ];
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi ke produk — bisa null jika produk sudah dihapus.
     * Gunakan nama_snapshot untuk tampilan, bukan product->nama_produk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
