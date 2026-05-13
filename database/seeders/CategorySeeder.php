<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Warung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warung = Warung::first();

        $kategori = [
            'Makanan Berat',
            'Minuman',
            'Cemilan & Snack',
            'Sembako',
            'Peralatan Rumah Tangga',
        ];

        foreach ($kategori as $nama) {
            Category::create([
                'warung_id' => $warung->id,
                'nama_kategori' => $nama,
            ]);
        }
    }
}
