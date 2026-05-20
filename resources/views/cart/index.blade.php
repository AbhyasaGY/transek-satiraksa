<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(count($cart) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Harga</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $item)
                            @php $total += $item['price'] * $item['quantity']; @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Rp
                                    {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-bold">
                                    {{ $item['quantity'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold">Rp
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <a href="{{ route('cart.remove', $id) }}"
                                        class="text-red-500 hover:text-red-700 font-bold">&times; Batal</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center border-t pt-4">
                    <div class="text-2xl font-extrabold text-gray-900">
                        Total Bayar: Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                    <a href="{{ route('cart.checkout') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg text-lg">
                        Lanjut ke Pembayaran (Midtrans) &rarr;
                    </a>
                </div>
                @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg mb-4">Keranjang Anda masih kosong.</p>
                    <a href="{{ url('/dashboard') }}" class="text-indigo-600 font-bold hover:underline">Kembali ke
                        Katalog</a>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>