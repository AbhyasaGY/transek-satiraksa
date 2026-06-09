<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
{
    // Looping otomatis untuk membuat 15 akun
    for ($i = 1; $i <= 15; $i++) {
        \App\Models\User::create([
            'name' => 'Mitra Bisnis ' . $i,
            'email' => 'mitra' . $i . '@satiraksa.com',
            'password' => bcrypt('password123'),
            'role' => 'Reseller',
            'phone' => '0812345678' . $i,
            'address' => 'Jalan Kemitraan No. ' . $i
        ]);
    }
}
}
