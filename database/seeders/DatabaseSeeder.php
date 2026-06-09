<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@satiraksa.com'], // Kondisi pencarian (Pengecualian)
            [
                'name' => 'Admin Satiraksa',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
                'phone' => '081111111111',
            ]
        );

        // 2. Akun Kasir
        User::firstOrCreate(
            ['email' => 'kasir@satiraksa.com'],
            [
                'name' => 'Kasir Utama',
                'password' => Hash::make('password123'),
                'role' => 'Kasir',
                'phone' => '082222222222',
            ]
        );

        // 3. Data Produk Dummy
        Product::firstOrCreate(
            ['sku' => 'TSHIRT-001'],
            [
                'name' => 'Satiraksa Classic Black T-Shirt',
                'description' => 'Kaos hitam polos bahan katun bambu 30s',
                'price' => 120000,
                'stock' => 50,
            ]
        );

        Product::firstOrCreate(
            ['sku' => 'HOODIE-001'],
            [
                'name' => 'Satiraksa Signature Hoodie',
                'description' => 'Hoodie tebal warna navy dengan logo bordir',
                'price' => 350000,
                'stock' => 20,
            ]
        );

        // 4. Looping 15 Akun Mitra Bisnis
        for ($i = 1; $i <= 15; $i++) {
            User::firstOrCreate(
                ['email' => 'mitra' . $i . '@satiraksa.com'],
                [
                    'name' => 'Mitra Bisnis ' . $i,
                    'password' => Hash::make('password123'),
                    'role' => 'Reseller',
                    'phone' => '0812345678' . $i,
                    'address' => 'Jalan Kemitraan No. ' . $i
                ]
            );
        }
    }
}
