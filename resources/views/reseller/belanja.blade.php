<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Katalog Grosir Mitra') }}
            </h2>
            <div class="space-x-4">
                <a href="{{ route('cart.index') }}"
                    class="text-sm font-bold bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg hover:bg-indigo-200 transition shadow-sm">
                    🛒 Lihat Keranjang PO
                </a>
                <a href="{{ route('reseller.dashboard') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-800 font-bold transition">
                    &larr; Dasbor
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div
                class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm font-semibold flex justify-between items-center">
                <span>✅ {{ session('success') }}</span>
                <a href="{{ route('cart.index') }}" class="underline text-green-800 hover:text-green-900">Buka Keranjang
                    PO</a>
            </div>
            @endif
            @if(session('error'))
            <div
                class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm font-semibold">
                ⚠️ {{ session('error') }}
            </div>
            @endif

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

                        <button type="button" onclick="toggleModal('modal-add-{{ $product->id }}')"
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

    @foreach($products as $product)
    <div id="modal-add-{{ $product->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                onclick="toggleModal('modal-add-{{ $product->id }}')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full p-6 border-t-4 border-indigo-600">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-indigo-900">Purchase Order (PO) Stok</h3>
                        <p class="text-sm text-gray-500 font-medium">{{ $product->name }}</p>
                    </div>

                    <div class="mb-6 bg-indigo-50 p-4 rounded-lg">
                        <label class="block text-indigo-900 text-sm font-bold mb-2">Pilih Jumlah Grosir (Maks:
                            {{ $product->stock }}):</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                            class="shadow-sm border-indigo-300 rounded-lg w-full py-2 px-3 text-indigo-900 font-bold leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('modal-add-{{ $product->id }}')"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl transition shadow-md">
                            Tambahkan ke PO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
    </script>
</x-app-layout>