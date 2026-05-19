<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Satiraksa Store - Sistem Kemitraan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-sans">

    <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="text-2xl font-extrabold text-indigo-600 tracking-tighter">
            SATIRAKSA<span class="text-gray-800">STORE</span>
        </div>
        <div>
            @auth
                <a href="{{ url('/dashboard') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Ke Dasbor Saya &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition">Masuk Aplikasi</a>
            @endauth
        </div>
    </nav>

    @if (session('status'))
        <div class="max-w-md mx-auto mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-center shadow" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-6 py-16">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
                Selamat Datang di Platform Satiraksa Store
            </h1>
            <p class="text-lg text-gray-600">Silakan pilih jenis akun pendaftaran di bawah ini sesuai dengan kebutuhan Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">

            <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100 flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Akun Pelanggan</h3>
                    <p class="text-gray-600 text-sm mb-6">Daftar sebagai pelanggan umum untuk melakukan transaksi pembelian retail produk Satiraksa secara langsung.</p>
                </div>
                <a href="{{ route('register', ['role' => 'pelanggan']) }}" class="w-full text-center py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-900 transition">
                    Daftar Pelanggan
                </a>
            </div>

            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 p-8 rounded-2xl shadow-xl border border-indigo-950 flex flex-col justify-between text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 bg-indigo-600 text-xs font-bold uppercase rounded-bl-xl tracking-widest text-indigo-100">
                    Eksklusif
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-2 text-indigo-300">Kemitraan Reseller</h3>
                    <p class="text-indigo-200 text-sm mb-6">Daftar sebagai mitra bisnis resmi. Dapatkan akses harga grosir khusus, manajemen stok dropship, dan penandatanganan kontrak kemitraan digital secara instan.</p>
                </div>
                <a href="{{ route('register', ['role' => 'reseller']) }}" class="w-full text-center py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 transition shadow-lg shadow-indigo-900/50">
                    Gabung Jadi Mitra Resmi
                </a>
            </div>

        </div>
    </main>

</body>
</html>
