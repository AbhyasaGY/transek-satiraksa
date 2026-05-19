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
    public function run(): void
    {
        // 1. Akun Admin (Full Access)
        User::create([
            'name' => 'Admin Satiraksa',
            'email' => 'admin@satiraksa.com',
            'password' => Hash::make('password123'), // Secure by Design: Password wajib di-hash
            'role' => 'Admin',
            'phone' => '081111111111',
        ]);

        // 2. Akun Kasir (Akses Modul POS & Midtrans)
        User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@satiraksa.com',
            'password' => Hash::make('password123'),
            'role' => 'Kasir',
            'phone' => '082222222222',
        ]);

        // 3. Akun Reseller (Akses Modul Generate Kontrak)
        User::create([
            'name' => 'Mitra Reseller 1',
            'email' => 'reseller@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'Reseller',
            'phone' => '083333333333',
            'address' => 'Jl. Pahlawan No. 10, Bandung',
        ]);

        // 4. Data Produk Dummy untuk POS
        Product::create([
            'sku' => 'TSHIRT-001',
            'name' => 'Satiraksa Classic Black T-Shirt',
            'description' => 'Kaos hitam polos bahan katun bambu 30s',
            'price' => 120000,
            'stock' => 50,
        ]);

        Product::create([
            'sku' => 'HOODIE-001',
            'name' => 'Satiraksa Signature Hoodie',
            'description' => 'Hoodie tebal warna navy dengan logo bordir',
            'price' => 350000,
            'stock' => 20,
        ]);
    }
}
