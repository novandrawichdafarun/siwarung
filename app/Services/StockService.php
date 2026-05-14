<?php
namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;

class StockService
{
  public function tambahStok(Product $product, int $jumlah, string $keterangan = null): void
  {
    $stokSebelum = $product->stok;
    $stokSesudah = $stokSebelum + $jumlah;

    $product->increment('stok', $jumlah);

    StockMovement::create([
      'warung_id' => $product->warung_id,
      'product_id' => $product->id,
      'user_id' => auth()->id(),
      'transaction_id' => null, // manual, bukan dari transaksi
      'type' => 'in',
      'quantity' => $jumlah,
      'stok_sebelum' => $stokSebelum,
      'stok_sesudah' => $stokSesudah,
      'keterangan' => $keterangan ?? 'Penambahan stok manual',
    ]);
  }

  public function kurangStok(Product $product, int $jumlah, int $transactionId, string $keterangan = null): void
  {
    abort_if(!$product->hasEnoughStock($jumlah), 422, 'Stok tidak mencukupi');

    $stokSebelum = $product->stok;
    $stokSesudah = $stokSebelum - $jumlah;

    $product->decrement('stok', $jumlah);

    StockMovement::create([
      'warung_id' => $product->warung_id,
      'product_id' => $product->id,
      'user_id' => auth()->id(),
      'transaction_id' => $transactionId,
      'type' => 'out',
      'quantity' => $jumlah,
      'stok_sebelum' => $stokSebelum,
      'stok_sesudah' => $stokSesudah,
      'keterangan' => $keterangan ?? 'Penjualan',
    ]);

  }
}