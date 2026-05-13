<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private array $produkMakanan = [
        'Nasi Goreng',
        'Mie Ayam',
        'Sate Ayam',
        'Gado-Gado',
        'Rendang',
        'Ayam Bakar',
        'Soto Ayam',
        'Bakso',
        'Pecel Lele',
        'Nasi Uduk'
    ];

    private array $produkMinuman = [
        'Es Teh Manis',
        'Es Jeruk',
        'Kopi Hitam',
        'Kopi Susu',
        'Teh Tarik',
        'Jus Alpukat',
        'Jus Jeruk',
        'Susu Cokelat',
        'Air Mineral',
        'Es Campur'
    ];

    private array $produkSnack = [
        'Keripik Singkong',
        'Keripik Pisang',
        'Keripik Kentang',
        'Kacang Goreng',
        'Roti Bakar',
        'Martabak Mini',
        'Pisang Goreng',
        'Tahu Isi',
        'Cireng',
        'Lumpia'
    ];

    public function definition(): array
    {
        $hargaBeli = fake()->randomElement([2000, 3000, 5000, 8000, 10000, 15000]);
        return [
            'warung_id' => null,
            'category_id' => null,
            'nama_produk' => fake()->randomElement(
                array_merge($this->produkMakanan, $this->produkMinuman, $this->produkSnack)
            ),
            'deskripsi' => null,
            'foto' => null,
            'harga_jual' => $hargaBeli + fake()->randomElement([1000, 2000, 3000, 5000]),
            'harga_beli' => $hargaBeli,
            'stok' => fake()->numberBetween(5, 100),
            'stok_minimum' => 5,
            'is_active' => true,
        ];
    }
}
