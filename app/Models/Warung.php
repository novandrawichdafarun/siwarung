<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Warung extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel eksplisit karena bukan plural bahasa Inggris.
     */
    protected $table = 'warung';

    protected $fillable = [
        'nama_warung',
        'slug',
        'alamat',
        'telepon',
        'logo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    // =========================================================
    // BOOT — auto-generate slug dari nama_warung
    // =========================================================

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Warung $warung) {
            if (empty($warung->slug)) {
                $warung->slug = static::generateUniqueSlug($warung->nama_warung);
            }
        });

        static::updating(function (Warung $warung) {
            if ($warung->isDirty('nama_warung') && empty($warung->getOriginal('slug'))) {
                $warung->slug = static::generateUniqueSlug($warung->nama_warung);
            }
        });
    }

    /**
     * Generate slug unik, tambahkan angka jika sudah ada.
     * Contoh: "Warung Bu Sari" → "warung-bu-sari", "warung-bu-sari-2", dst.
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $slug      = Str::slug($name);
        $original  = $slug;
        $counter   = 2;

        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    /**
     * Owner warung (hanya 1 user ber-role owner per warung).
     */
    public function owner(): HasOne
    {
        return $this->hasOne(User::class)->where('role', 'owner');
    }

    /**
     * Semua user yang tergabung (owner + kasir).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Kasir-kasir warung ini.
     */
    public function kasir(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'kasir');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function commissionLedgers(): HasMany
    {
        return $this->hasMany(CommissionLedger::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
