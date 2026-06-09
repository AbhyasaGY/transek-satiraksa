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
        // 1. Akun Admin (Full Access) - Dibutuhkan Fasha untuk Brute Force
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

        // 3. Data Produk Dummy untuk POS - Dibutuhkan Hilmy untuk cek Keranjang & Checkout
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

        // 4. Looping otomatis 15 Akun Mitra Bisnis (Reseller) - Untuk tugas Lapis 1-3 & IDOR
        for ($i = 1; $i <= 15; $i++) {
            User::create([
                'name' => 'Mitra Bisnis ' . $i,
                'email' => 'mitra' . $i . '@satiraksa.com',
                'password' => Hash::make('password123'),
                'role' => 'Reseller',
                'phone' => '0812345678' . $i,
                'address' => 'Jalan Kemitraan No. ' . $i
            ]);
        }
    }
}