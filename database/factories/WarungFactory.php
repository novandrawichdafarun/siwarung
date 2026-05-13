<?php

namespace Database\Factories;

use App\Models\Warung;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Warung>
 */
class WarungFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $namaWarung = fake()->randomElement([
            'Warung Bu ' . fake()->firstNameFemale(),
            'Warung Pak ' . fake()->firstNameMale(),
            'Toko ' . fake()->lastName(),
            'Warung kopi ' . fake()->word()
        ]);
        return [
            'nama_warung' => $namaWarung,
            'slug' => Str::slug($namaWarung),
            'alamat' => fake()->address(),
            'telepon' => '08' . fake()->numerify('##########'),
            'logo' => null,
            'is_active' => true,
        ];
    }
}
