<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private array $kategori = [
        'Makanan Berat',
        'Minuman',
        'Cemilan & Snack',
        'Sembako',
        'Peralatan Rumah Tangga',
        'Lainnya'
    ];
    public function definition(): array
    {
        return [
            'warung_id' => null,
            'nama_kategori' => fake()->randomElement($this->kategori),
        ];
    }
}
