<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Katalog Produk') }}
            </h2>
            <div class="space-x-4">
                <a href="{{ route('cart.index') }}"
                    class="text-sm font-bold bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg hover:bg-indigo-200 transition shadow-sm">
                    🛒 Lihat Keranjang
                </a>
                <a href="{{ route('pelanggan.dashboard') }}"
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
                <a href="{{ route('cart.index') }}" class="underline text-green-800 hover:text-green-900">Buka
                    Keranjang</a>
            </div>
            @endif
            @if(session('error'))
            <div
                class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm font-semibold">
                ⚠️ {{ session('error') }}
            </div>
            @endif

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

                        <button type="button" onclick="toggleModal('modal-add-{{ $product->id }}')"
                            class="w-full bg-gray-900 hover:bg-black text-white font-bold py-2.5 px-4 rounded-xl transition shadow-md">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white p-12 text-center rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Katalog Kosong</h3>
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
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full p-6">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Masukkan Keranjang</h3>
                        <p class="text-sm text-gray-500 font-medium">{{ $product->name }}</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Jumlah (Maks:
                            {{ $product->stock }}):</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                            class="shadow-sm border-gray-300 rounded-lg w-full py-2 px-3 text-gray-900 font-bold leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('modal-add-{{ $product->id }}')"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl transition shadow-md">
                            Konfirmasi
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