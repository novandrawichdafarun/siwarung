<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warung = Warung::first();

        User::create([
            'name' => 'Sari Pemilik',
            'email' => 'owner@siwarung.test',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'warung_id' => $warung->id,
        ]);

        User::create([
            'name' => 'Budi Kasir',
            'email' => 'kasir@siwarung.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'warung_id' => $warung->id,
        ]);
    }
}
