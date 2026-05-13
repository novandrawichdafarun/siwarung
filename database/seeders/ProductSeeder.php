<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Warung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warung = Warung::first();
        $categories = Category::all()->keyBy('nama_kategori');

        $produk = [
            'Makanan Berat' => [
                ['nama_produk' => 'Nasi Goreng Biasa', 'harga_jual' => 15000, 'harga_beli' => 8000, 'stok' => 20],
                ['nama_produk' => 'Nasi Goreng Spesial', 'harga_jual' => 20000, 'harga_beli' => 12000, 'stok' => 15],
                ['nama_produk' => 'Mie Ayam', 'harga_jual' => 12000, 'harga_beli' => 7000, 'stok' => 25],
                ['nama_produk' => 'Bakso Urat', 'harga_jual' => 15000, 'harga_beli' => 9000, 'stok' => 18],
            ],
            'Minuman' => [
                ['nama_produk' => 'Es Teh Manis', 'harga_jual' => 5000, 'harga_beli' => 2000, 'stok' => 50],
                ['nama_produk' => 'Kopi Hitam', 'harga_jual' => 6000, 'harga_beli' => 2500, 'stok' => 40],
                ['nama_produk' => 'Air Mineral 600ml', 'harga_jual' => 4000, 'harga_beli' => 2500, 'stok' => 60],
                ['nama_produk' => 'Jus Alpukat', 'harga_jual' => 12000, 'harga_beli' => 7000, 'stok' => 20],
            ],
            'Cemilan & Snack' => [
                ['nama_produk' => 'Chitato 68gr', 'harga_jual' => 12000, 'harga_beli' => 9500, 'stok' => 30],
                ['nama_produk' => 'Oreo Original', 'harga_jual' => 5000, 'harga_beli' => 3500, 'stok' => 45],
                ['nama_produk' => 'Indomie Goreng', 'harga_jual' => 4000, 'harga_beli' => 2800, 'stok' => 100],
            ],
            'Rokok' => [
                // Stok minimal rokok biasanya lebih tinggi
                ['nama_produk' => 'Gudang Garam Merah', 'harga_jual' => 24000, 'harga_beli' => 22000, 'stok' => 3, 'stok_minimal' => 10],
                ['nama_produk' => 'Sampoerna Mild', 'harga_jual' => 28000, 'harga_beli' => 25500, 'stok' => 5, 'stok_minimal' => 10],
            ],
            'Sembako' => [
                ['nama_produk' => 'Beras 5kg', 'harga_jual' => 75000, 'harga_beli' => 68000, 'stok' => 10],
                ['nama_produk' => 'Minyak Goreng 1L', 'harga_jual' => 18000, 'harga_beli' => 15000, 'stok' => 20],
                ['nama_produk' => 'Gula Pasir 1kg', 'harga_jual' => 16000, 'harga_beli' => 14000, 'stok' => 15],
            ],
        ];

        foreach ($produk as $namaKategori => $items) {
            $category = $categories->get($namaKategori);

            foreach ($items as $item) {
                Product::create([
                    'warung_id' => $warung->id,
                    'category_id' => $category?->id,
                    'nama_produk' => $item['nama_produk'],
                    'harga_jual' => $item['harga_jual'],
                    'harga_beli' => $item['harga_beli'],
                    'stok' => $item['stok'],
                    'stok_minimal' => $item['stok_minimal'] ?? 5,
                    'is_active' => true,
                ]);
            }
        }
    }
}
