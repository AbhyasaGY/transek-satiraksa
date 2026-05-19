<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Katalog Produk') }}
            </h2>
            <a href="{{ route('pelanggan.dashboard') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-bold transition">
                &larr; Kembali ke Dasbor
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h3 class="text-2xl font-extrabold text-gray-900">Belanja Sekarang</h3>
                <p class="text-gray-500">Temukan produk terbaik dari Satiraksa Store.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="h-48 bg-gray-100 flex items-center justify-center border-b border-gray-100">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>

                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mb-4">Sisa stok: <span
                                class="font-semibold text-gray-700">{{ $product->stock }} pcs</span></p>

                        <div class="text-xl font-extrabold text-indigo-600 mb-5">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        <button
                            onclick="alert('Fitur Keranjang Belanja & Checkout Mandiri akan hadir pada pengembangan selanjutnya!')"
                            class="w-full bg-gray-900 hover:bg-black text-white font-bold py-2.5 px-4 rounded-xl transition shadow-md">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white p-12 text-center rounded-2xl shadow-sm border border-gray-100">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">Katalog Kosong</h3>
                    <p class="text-gray-500 mt-1">Belum ada produk yang tersedia untuk saat ini.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>