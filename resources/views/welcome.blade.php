<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Satiraksa Store - Sistem Kemitraan & Penjualan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-sans">

    <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="text-2xl font-extrabold text-indigo-600 tracking-tighter">
            SATIRAKSA<span class="text-gray-800">STORE</span>
        </div>

        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Ke Dasbor Saya &rarr;</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition mr-6">Masuk (Login)</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="font-bold bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition shadow-md">Daftar Akun</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-16 md:py-24 flex flex-col md:flex-row items-center gap-12">

        <div class="w-full md:w-1/2 flex flex-col items-start text-left">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold mb-4">
                Sistem Informasi Terintegrasi
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                Kelola Penjualan & Kemitraan Lebih Cerdas.
            </h1>
            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                Satiraksa Store menghadirkan platform Point of Sales (POS) modern yang dilengkapi dengan manajemen inventaris otomatis, pembayaran digital Midtrans, dan pembuatan kontrak kemitraan instan.
            </p>

            <div class="flex gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:bg-indigo-700 transition">Buka Aplikasi</a>
                @else
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:bg-indigo-700 transition">Mulai Sekarang</a>
                @endauth
            </div>
        </div>

        <div class="w-full md:w-1/2">
            <div class="relative bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute -bottom-8 -left-4 w-24 h-24 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>

                <h3 class="text-xl font-bold mb-4 text-gray-800">Fitur Unggulan</h3>
                <ul class="space-y-4">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Kasir (POS) & Real-time Stok
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Integrasi Payment Gateway Midtrans
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Smart Role Redirect (Admin, Kasir, Reseller)
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Auto-Generate Kontrak PDF
                    </li>
                </ul>
            </div>
        </div>

    </main>

    <footer class="border-t border-gray-200 mt-12 py-8 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} Satiraksa Store. Proyek Pengembangan Web Terintegrasi.
    </footer>

</body>
</html>
