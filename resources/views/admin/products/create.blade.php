<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-indigo-600 transition">&larr;
                Kembali</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Produk Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border-t-4 border-indigo-600">

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Produk <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="shadow-sm border-gray-300 rounded-lg w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                            required placeholder="Contoh: Satiraksa Classic T-Shirt">
                        @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="sku" class="block text-gray-700 text-sm font-bold mb-2">Kode SKU <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                            class="shadow-sm border-gray-300 rounded-lg w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('sku') border-red-500 @enderror"
                            required placeholder="Contoh: STS-TS-001">
                        @error('sku') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Harga Normal (Rp)
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" min="0"
                                class="shadow-sm border-gray-300 rounded-lg w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('price') border-red-500 @enderror"
                                required placeholder="Contoh: 150000">
                            @error('price') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Stok Awal <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0"
                                class="shadow-sm border-gray-300 rounded-lg w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('stock') border-red-500 @enderror"
                                required>
                            @error('stock') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 border-t pt-6">
                        <a href="{{ route('products.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2.5 px-6 rounded-xl transition">Batal</a>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition">Simpan
                            Produk</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
