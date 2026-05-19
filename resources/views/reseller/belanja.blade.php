<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Katalog Grosir Mitra') }}
            </h2>
            <a href="{{ route('reseller.dashboard') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-bold transition">
                &larr; Kembali ke Dasbor
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8 p-6 bg-indigo-900 rounded-2xl shadow-lg text-white flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-extrabold">Pesanan Stok Reseller</h3>
                    <p class="text-indigo-200 mt-1">Nikmati potongan harga khusus 20% untuk semua produk sebagai mitra
                        resmi kami.</p>
                </div>
                <div class="hidden md:block">
                    <span
                        class="bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">Reseller
                        Tier 1</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                <div
                    class="bg-white rounded-2xl shadow-sm border-2 border-indigo-50 overflow-hidden hover:border-indigo-300 transition duration-300 relative">
                    <div
                        class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-bl-lg z-10">
                        -20%
                    </div>

                    <div class="h-40 bg-gray-50 flex items-center justify-center border-b border-gray-100">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>

                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mb-3">Sisa di Gudang: <span
                                class="font-bold text-indigo-600">{{ $product->stock }} pcs</span></p>

                        <div class="text-xs text-gray-400 line-through mb-1">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <div class="text-xl font-extrabold text-green-600 mb-5">
                            Rp {{ number_format($product->price * 0.8, 0, ',', '.') }}
                        </div>

                        <button onclick="alert('Fitur Purchase Order (PO) Massal akan segera hadir!')"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl transition shadow">
                            Buat PO Stok
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white p-8 text-center rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-gray-500">Katalog stok sedang dikosongkan.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>